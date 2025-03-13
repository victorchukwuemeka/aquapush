<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use App\Models\DigitalOceanDroplet;
use phpseclib3\Net\SSH2;


class DigitalOceanController extends Controller
{   
   
    //private $ssh = new 

    
    public function getDroplet($droplet_id){
        //dd($droplet_id);
        $digital_ocean_droplet = DigitalOceanDroplet::where('droplet_id', $droplet_id)->first();
        
        if (!$digital_ocean_droplet) {
            return view('dashboard.droplets.show-droplet', ['error' => 'Droplet not found']);
        }
        
        try {
            $client = new Client([
                'base_uri' => 'https://api.digitalocean.com/v2/',
                'headers'  => [
                    'Authorization' => "Bearer {$digital_ocean_droplet->api_token}",
                    'Accept'        => 'application/json',
                ],
            ]);
            
            $response = $client->get("droplets/{$droplet_id}");
            $dropletData = json_decode($response->getBody(), true);
            return view('dashboard.droplets.show-droplet', compact('dropletData'));
        } catch (\Exception $e) {
            return view('droplets.show', ['error' => 'Failed to fetch droplet details']);
        }
    }

    public function configureDeployment($droplet_id){
         
         // Fetch droplet detail
         //$droplet = $this->digitalOcean->droplets()->getById($dropletId);
         $droplet = DigitalOceanDroplet::where('droplet_id', $droplet_id)->first();
         
         // Fetch user's connected repositories
         //$repositories = auth()->user()->connectedRepositories;
         
         // Fetch server capabilities
         $phpVersions = ['7.4', '8.0', '8.1', '8.2'];
         $webServers = ['nginx', 'apache'];
         
         return view('dashboard.droplets.configure', [
            'droplet' => $droplet,
            'phpVersions' => $phpVersions,
            'webServers' => $webServers
        ]);
    }
    


    public function connectToDroplet($droplet_id){
        
        // Fetch the Droplet by ID
        $droplet = DigitalOceanDroplet::findOrFail($droplet_id);
        
        if (!$droplet) {
            return ['error' => 'Droplet Not Found'];
        }

         // Check if the SSH connection was previously established
        if ($droplet->ssh_connected) {
            return ['success' => true, 'ssh' => $this->getActiveSSHConnection($droplet)];
        }

        //dd($droplet->ip_address);
        // Connect to the Droplet via SSH
        $ssh = new SSH2($droplet->ip_address);
        if (!$ssh->login($droplet->ssh_username, $droplet->ssh_private_key)) {
            return ['error' => 'SSH login failed. Please check your credentials.'];
        }
        
        return ['success' => true, 'ssh' => $ssh];
    }

    // This function should retrieve the existing SSH connection (from session, cache, etc.)
    private function getActiveSSHConnection($droplet){
        // Example: Retrieve stored SSH connection (Modify as needed)
        return new SSH2($droplet->ip_address);
    }


    public function setupProject(Request $request, $droplet_id){
        //dd($droplet_id);
        //dd($request);
        //$this->validateSetupRequest($request);

        // Establish SSH Connection
        /*$connection = $this->connectToDroplet($droplet_id);
        if (isset($connection['error'])) {
            return back()->withErrors(['ssh_error' => $connection['error']]);
        }*/
        
        //$ssh = $connection['ssh'];
        $digital_ocean_droplet = DigitalOceanDroplet::where('id', $droplet_id)->first();
        //dd($digital_ocean_droplet);
        $host = $digital_ocean_droplet->ip_address;
        $port = 22;
        
        $ssh = new SSH2($host,$port);
        //dd($ssh);
        if($this->installDependencies($ssh, $request)){
            dd('worked');
        }

        $this->setupWebServerAndDatabase($ssh, $request);
        $this->deployApplication($ssh, $request);

        return redirect()->back()->with('success', 'Project setup completed successfully!');
       
    }
    

    private function validateSetupRequest($request){
        $request->validate([
            'repository_url' => 'required|string',
            'php_version' => 'required|string',
            'web_server' => 'required|string',
            'database' => 'nullable|string',
            //'npm_install' => 'nullable|boolean',
        ]);
    }

    /*private function installDependencies($ssh, $request):bool{
        
        // Step 1: Install PHP
        $ssh->exec("sudo apt-get update");
        $ssh->exec("sudo apt-get install -y php{$request->input('php_version')}");
        
        // Step 2: Install and Setup Database
        if ($request->input('database') === 'mariadb') {
            $ssh->exec("sudo apt-get install -y mariadb-server");
            $ssh->exec("sudo mysql -e \"CREATE DATABASE {$request->input('db_name')};\"");
            $ssh->exec("sudo mysql -e \"CREATE USER '{$request->input('db_user')}'@'localhost' IDENTIFIED BY '{$request->input('db_password')}';\"");
            $ssh->exec("sudo mysql -e \"GRANT ALL PRIVILEGES ON {$request->input('db_name')}.* TO '{$request->input('db_user')}'@'localhost';\"");
            $ssh->exec("sudo mysql -e \"FLUSH PRIVILEGES;\"");
        }
        
    }*/

    private function installDependencies($ssh, $request): bool {
        // Step 1: Update package list and upgrade
        if ($ssh->exec("sudo apt-get update") !== 0) {
            //dd('kkk');
            return false; // Return false if update fails
        }
    
        // Step 2: Install PHP
        if ($ssh->exec("sudo apt-get install -y php{$request->input('php_version')}") !== 0) {
            return false; // Return false if PHP installation fails
        }
    
        // Step 3: Install and Setup Database (MariaDB)
        if ($request->input('database') === 'mariadb') {
            // Install MariaDB
            if ($ssh->exec("sudo apt-get install -y mariadb-server") !== 0) {
                return false; // Return false if MariaDB installation fails
            }
    
            // Create database and user
            $dbName = $request->input('db_name');
            $dbUser = $request->input('db_user');
            $dbPassword = $request->input('db_password');
    
            // Create database
            if ($ssh->exec("sudo mysql -e \"CREATE DATABASE {$dbName};\"") !== 0) {
                return false; // Return false if database creation fails
            }
    
            // Create user and set privileges
            if ($ssh->exec("sudo mysql -e \"CREATE USER '{$dbUser}'@'localhost' IDENTIFIED BY '{$dbPassword}';\"") !== 0) {
                return false; // Return false if user creation fails
            }
    
            if ($ssh->exec("sudo mysql -e \"GRANT ALL PRIVILEGES ON {$dbName}.* TO '{$dbUser}'@'localhost';\"") !== 0) {
                return false; // Return false if granting privileges fails
            }
    
            // Flush privileges
            if ($ssh->exec("sudo mysql -e \"FLUSH PRIVILEGES;\"") !== 0) {
                return false; // Return false if flushing privileges fails
            }
        }
    
        return true; // Everything worked, return true
    }
    


    //  Setup Web Server and Deploy Laravel App
    private function setupWebServerAndDatabase($ssh, $request){
        // Step 3: Clone GitHub Repository
        $ssh->exec("git clone {$request->input('repository_url')} /var/www/laravel");
        
        // Step 4: Set Up Web Server
        if ($request->input('web_server') === 'nginx') {
            $ssh->exec("sudo apt-get install -y nginx");
            $ssh->exec("sudo cp /var/www/laravel/nginx.conf /etc/nginx/sites-available/laravel");
            $ssh->exec("sudo ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/");
            $ssh->exec("sudo systemctl restart nginx");
        } elseif ($request->input('web_server') === 'apache') {
            $ssh->exec("sudo apt-get install -y apache2");
            $ssh->exec("sudo systemctl restart apache2");
        }
    }

    private function deployApplication($ssh, $request){
         // Step 5: Set Up Environment Variables
         $ssh->exec("echo 'DB_DATABASE={$request->input('db_name')}' >> /var/www/laravel/.env");
         $ssh->exec("echo 'DB_USERNAME={$request->input('db_user')}' >> /var/www/laravel/.env");
         $ssh->exec("echo 'DB_PASSWORD={$request->input('db_password')}' >> /var/www/laravel/.env");
         
         // Step 6: Run Composer and Migrations
         $ssh->exec("cd /var/www/laravel && composer install --no-dev --optimize-autoloader");
         $ssh->exec("cd /var/www/laravel && php artisan migrate --force");
         return redirect()->back()->with('success', 'Project setup completed successfully!');
    }
}
