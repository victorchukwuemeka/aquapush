<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\DigitalOceanDroplet;
use App\Events\DeploymentStatusUpdated;
use Illuminate\Support\Facades\Log;

class DeploymentController extends Controller
{   
    
    //variable for deployment storage
    private $store_deployment;

    private $ssh_key_in_session;

    private $ip_address;

    //available droplet sizes
    private $dropletSizes = [
        's-1vcpu-1gb' => 'Basic: 1 vCPU, 1 GB RAM',
        's-1vcpu-2gb' => 'Standard: 1 vCPU, 2 GB RAM',
        's-2vcpu-2gb' => 'Standard: 2 vCPU, 2 GB RAM',
        's-2vcpu-4gb' => 'Standard: 2 vCPU, 4 GB RAM',
        's-2vcpu-8gb' => 'Standard: 2 vCPU, 8 GB RAM',
        // Add more sizes as needed
    ];
     
    // Available regions
    private $regions = [
        'nyc1' => 'New York 1',
        'sfo2' => 'San Francisco 2',
        'ams3' => 'Amsterdam 3',
    ];

    // Available images for your vm 
    private $images = [
        'ubuntu-20-04-x64' => 'Ubuntu 20.04 (x64)',
        'centos-7-x64' => 'CentOS 7 (x64)',
        'debian-11-x64' => 'Debian 11 (x64)',
    ];
     
    public function ssh(){
        return view('deploy.ssh-key-get');
    }

    public function index(){
        
        return view('deploy.create');
    }
    
    public function create()
    {
        $api_token = session('digitalocean.api_token');
        $droplet_size = session('digitalocean.droplet_size');
        
        return view('deploy.create', [
            'dropletSizes' => $this->dropletSizes,
            'regions' => $this->regions,
            'images' => $this->images,
            'api_token' => $api_token,
            'droplet_size' => $droplet_size,
        ]);
    }

    public function deploy(Request $request){
        // dd($request);
        // Validate the incoming request data
        $validatedData = $this->validateDeploymentData($request);

        
        //check if it's a laravel repo and store in the database.
        if ($this->check_if_repo_is_laravel($validatedData['repository'])) {
             //storing the droplet in my db so it can be tracked .
            $this->store_deployment = $this->storeDeployment($validatedData);
        }
    

        
        // Store the token and droplet size in session  as needed
        // For demonstration, we're using the session
        session([
            'digitalocean.api_token' => $validatedData['api_token'],
            'digitalocean.droplet_size' => $validatedData['droplet_size'],
        ]);

         // Check droplet limit so yu will not push more droplet.
         $dropletLimitCheck = $this->checkDropletLimit($validatedData['api_token']);
         if ($dropletLimitCheck['status'] === 'error') {
             return redirect()->route('digitalocean.config')->with('error', $dropletLimitCheck['message']);
         }

         
        
        //confirm its a laravel repo before creating a droplet
        if ($this->check_if_repo_is_laravel($validatedData['repository'])) {
            //dd($validatedData['ssh_key']);
            $this->ssh_key_in_session = $validatedData['ssh_key'];
            // Check if the SSH key exists or add it
            $sshFingerprint = $this->getOrCreateSSHKey($validatedData['api_token']);
            //dd($this->ssh_key_in_session);

            // Call the method to create the droplet
            $droplet = $this->createDroplet(
                $validatedData['api_token'],
                $validatedData['droplet_name'], 
                $validatedData['region'],
                $validatedData['droplet_size'],
                $validatedData['image'], 
                $validatedData['repository'],
                $sshFingerprint
            );
            //$this->pollDropletStatus($validatedData['api_token'],)
            //$this->start_deployment($this->store_deployment);
            return redirect()->route('dashboard')->with('success', 'Droplet created successfully!');
        }else{
            return redirect()->route('error-not-laravel');
        }
        
    }
    
    
    /**
     * function used for storing the deployment in our database.
     */
    private function storeDeployment($validatedData)
    {
        return  DigitalOceanDroplet::create([
            'user_id' => Auth::user()->id,
            'api_token' => $validatedData['api_token'],
            'droplet_size' => $validatedData['droplet_size'],
            'repository' => $validatedData['repository'],
            'region' => $validatedData['region'],
            'image' => $validatedData['image'],
            'droplet_name' => $validatedData['droplet_name'],
            'ip_address' => $this->ip_address,
            'status' => 'pending',
            

        ]);
    }
    
    
    /**
     * function used for validating the request, from the 
     * deployment form.
     */
    private function validateDeploymentData($data){
        return $data->validate([
            'api_token' => 'required|string',
            'ssh_key' => 'required|string',
            'droplet_size' => 'required|string',
            'repository' => 'required|string',
            'region' => 'required|string',
            'image' => 'required|string',
            'droplet_name' => 'required|string',
        ]);
    }

    //start monitorig the deployment.
    /*public function start_deployment($store_deployment){
        // Ensure the deployment object exists
        if (!$store_deployment) {
            // Handle the case where the deployment doesn't exist
            // For example, throw an exception or return a response
            return response()->json(['error' => 'Deployment not found'], 404);
        }
        $store_deployment->update(['status' => 'inprogress']);
        event(new DeploymentStatusUpdated($store_deployment));
        
        sleep(2);
        $store_deployment->update(['status' => 'active']);
        event(new DeploymentStatusUpdated($store_deployment));
    }*/


    //getting the deployment status.
    public function get_deployment_status($id){
        $store_deployment_status = DigitalOceanDroplet::findOrFail($id);
        return response()->json(['status' => $store_deployment_status->status]);
    }
    
    //handles creating of droplet in the digitalOcean .
    public function createDroplet(
        $apiToken, 
        $dropletName, 
        $region,
        $size, 
        $image, 
        $githubRepo,
        $ssh_key
    ){
        $client = new Client();

        // Send request to DigitalOcean API to create droplet
        $response = $client->post('https://api.digitalocean.com/v2/droplets', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $dropletName,
                'region' => $region,
                'size' => $size,
                'image' => $image,  // Example: 'ubuntu-20-04-x64'
                'ssh_keys' => $ssh_key,
                'backups' => false,
                //'user_data' => $this->getUserData($githubRepo),
            ],
        ]);

        //get the details of your droplet.
        $body = json_decode($response->getBody()->getContents(), true);
        $dropletId = $body['droplet']['id'];
        
        //getting the droplet ip address after its created 
        $this->getIpAddress($dropletId,$apiToken);
        
        // Save droplet ID to track status
        $this->store_deployment->update(['droplet_id' => $dropletId, 'status' => 'inprogress']);
    
        // Poll droplet status periodically
        $this->pollDropletStatus($apiToken, $dropletId);   
    }
    
    /**
     * this function is for getting the ip address.
     */
    public function getIpAddress($dropletId, $apiToken){
        $client = new Client();
        $response_for_get_call = $client->get("https://api.digitalocean.com/v2/droplets/{$dropletId}",[
            'headers'=>[
                'Authorization' => 'Bearer'. $apiToken,
                'Context-type' => 'application/json',
            ]
        ]);
        $body_from_get_call = json_decode($response_for_get_call->getBody()->getContents(), true);
        $networks = $body_from_get_call['droplet']['networks']['v4'];

        foreach ($networks as $network) {
            if ($network['type'] === 'public') {
                return $this->ip_address = $network['ip_address'];
            }
        }

    }

    /**
     * using the deployment status to monitor it .
     */
    public function pollDropletStatus($apiToken, $dropletId){
        $client = new Client();
        $status = '';
        $attempt = 0;

        while ($attempt < 10 && $status !== 'active') {
            $attempt++;

            // Get droplet details from DigitalOcean API
            $response = $client->get("https://api.digitalocean.com/v2/droplets/{$dropletId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                    'Content-Type' => 'application/json',
                ]
            ]);


            $body = json_decode($response->getBody()->getContents(), true);
            $status = $body['droplet']['status'];

           // dd($body);
            //LOg stats
            Log::info('Droplet Status'.$status);

            //if droplet is active update 
            if ($status === 'active') {
                $this->store_deployment->update(['status' => 'active']);
                break;
            }
            
            // Wait for some time before checking again
            sleep(10);
        }

        if ($status !== 'active') {
            Log::error('Droplet creation failed after multiple attempts.');
            $this->store_deployment->update(['status' => 'failed']);
        }

    }

    

    //making sure the repo a laravel project .
    public function check_if_repo_is_laravel($repo): bool
    {    
        //split the repo string .
        [$username , $repo_name] = explode('/', $repo);

        //list branches 
        $branches = ['master', 'main'];

        foreach($branches as $branch){

            $url = "https://raw.githubusercontent.com/$username/$repo_name/$branch/composer.json";
            try {
                $composer_json_content = @file_get_contents($url);
                if ($composer_json_content) {
                    $composer_data = json_decode($composer_json_content, true);
                    if (isset($composer_data['require']['laravel/framework'])) {
                        return true;
                    }
                }
            } catch (\Throwable $th) {
                continue;
            }
        }
        return false;
    }
    
    /*private function getUserData($githubRepo)
    {
        // Validate and sanitize GitHub repository input
        $repoName = escapeshellarg($githubRepo);
    
        return <<<BASH
    #!/bin/bash
    set -e  # Exit immediately if a command exits with a non-zero status
    LOGFILE='/var/log/deployment.log'
    
    exec > >(tee -a \$LOGFILE) 2>&1  # Log all output to file
    
    {
        echo 'Starting deployment process...'
    
        # Update and install dependencies
        apt-get update -y
        apt-get install -y git
        apt-get install -y apache2
        apt-get install -y php libapache2-mod-php php-mbstring php-xml php-bcmath php-cli php-curl php-zip php-mysql composer
    
        # Clone the GitHub repo
        cd /var/www/html
        git clone https://github.com/$repoName
        cd \$(basename $repoName .git)  # Change directory to the cloned repo
    
        # Install composer dependencies
        composer install
    
        # Set up .env file
        cp .env.example .env
        php artisan key:generate
        php artisan migrate --force
    
        # Set file permissions
        chown -R www-data:www-data /var/www/html
        chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache
    
        # Apache setup
        cat <<EOL > /etc/apache2/sites-available/laravel-app.conf
    <VirtualHost *:80>
        ServerAdmin admin@example.com
        DocumentRoot /var/www/html/\$(basename $repoName .git)/public
        <Directory /var/www/html/\$(basename $repoName .git)/public>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted
        </Directory>
        ErrorLog \${APACHE_LOG_DIR}/laravel-app-error.log
        CustomLog \${APACHE_LOG_DIR}/laravel-app-access.log combined
    </VirtualHost>
    EOL
    
        # Enable Apache site and modules
        a2dissite 000-default.conf
        a2ensite laravel-app
        a2enmod rewrite
    
        # Restart Apache to apply changes
        systemctl restart apache2
    
        echo 'Deployment completed successfully.'
    } || {
        echo 'Deployment failed. Check \$LOGFILE for details.'
        exit 1
    }
    BASH;
    }*/

    
 /*   private function getUserData($githubRepo)
{
    // Validate and sanitize GitHub repository input
    $repoName = escapeshellarg($githubRepo);
    $dbName = strtolower(str_replace('-', '_', basename($repoName, '.git'))) . '_db'; // Create database name dynamically based on repo name
    $dbUser = strtolower(str_replace('-', '_', basename($repoName, '.git'))) . '_user'; // Create database user dynamically based on repo name
    $dbPassword = 'securepassword'; // Set a default password or generate dynamically if needed

    return <<<BASH
#!/bin/bash
set -e  # Exit immediately if a command exits with a non-zero status
LOGFILE='/var/log/deployment.log'

exec > >(tee -a \$LOGFILE) 2>&1  # Log all output to file

{
    echo 'Starting deployment process...'

    # Update and install dependencies
    apt-get update -y
    apt-get install -y git
    apt-get install -y apache2
    apt-get install -y software-properties-common  # Needed for adding PPAs
    apt-get install -y mariadb-server

    # Add the repository for the latest PHP version
    add-apt-repository ppa:ondrej/php -y
    apt-get update -y

    # Install the latest PHP version compatible with Composer (e.g., PHP 8.2 or 8.3)
    apt-get install -y php8.2 libapache2-mod-php8.2 php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-cli php8.2-curl php8.2-zip php8.2-mysql composer

    # Start and secure MariaDB
    systemctl start mariadb
    mysql_secure_installation <<EOF

y
rootpassword 
rootpassword
y
y
y
y
EOF

    # Create database and user dynamically
    mysql -uroot -prootpassword -e "CREATE DATABASE \$dbName;"
    mysql -uroot -prootpassword -e "CREATE USER '\$dbUser'@'localhost' IDENTIFIED BY '\$dbPassword';"
    mysql -uroot -prootpassword -e "GRANT ALL PRIVILEGES ON \$dbName.* TO '\$dbUser'@'localhost';"
    mysql -uroot -prootpassword -e "FLUSH PRIVILEGES;"

    # Clone the GitHub repo
    cd /var/www/html
    git clone https://github.com/$repoName
    cd \$(basename $repoName .git)  # Change directory to the cloned repo

    # Install composer dependencies
    composer install

    # Set up .env file with dynamic DB values
    cp .env.example .env
    # sed -i "s/DB_DATABASE=.*//*DB_DATABASE=\$dbName/" .env
    /*sed -i "s/DB_USERNAME=.*//*DB_USERNAME=\$dbUser/" .env
    sed -i "s/DB_PASSWORD=.*//*DB_PASSWORD=\$dbPassword/" .env
    php artisan key:generate
    php artisan migrate --force

    # Set file permissions
    chown -R www-data:www-data /var/www/html
    chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

    # Apache setup
    cat <<EOL > /etc/apache2/sites-available/laravel-app.conf
<VirtualHost *:80>
    ServerAdmin admin@example.com
    DocumentRoot /var/www/html/\$(basename $repoName .git)/public
    <Directory /var/www/html/\$(basename $repoName .git)/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog \${APACHE_LOG_DIR}/laravel-app-error.log
    CustomLog \${APACHE_LOG_DIR}/laravel-app-access.log combined
</VirtualHost>
EOL

    # Enable Apache site and modules
    a2dissite 000-default.conf
    a2ensite laravel-app
    a2enmod rewrite

    # Restart Apache to apply changes
    systemctl restart apache2

    echo 'Deployment completed successfully.'
} || {
    echo 'Deployment failed. Check \$LOGFILE for details.'
    exit 1
}
BASH;
}*/

 
    //handling the amount of droplet in digitalOcean 
    private function checkDropletLimit($apiToken)
    {    
        //dd($apiToken);
        //try {
            $client = new Client();
            //dd($client);
            $response = $client->get('https://api.digitalocean.com/v2/account', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],
            ]);
            //dd($response);
            $data = json_decode($response->getBody()->getContents(), true);
            $dropletLimit = $data['account']['droplet_limit'];

            //dd($dropletLimit);
            //dd($data['account']['droplet_count']);
            // Step 2: Get the current number of droplets
            $dropletsResponse = $client->get('https://api.digitalocean.com/v2/droplets', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiToken,
                ],
            ]);
            
            
            $dropletsData = json_decode($dropletsResponse->getBody()->getContents(), true);
            $dropletCount = count($dropletsData['droplets']);

            
            //dd($dropletsData);
            
            if ($dropletCount >= $dropletLimit) {
                return [
                    'status' => 'error',
                    'message' => 'You have reached your droplet creation limit. Please delete some droplets or contact DigitalOcean support.'
                ];
            }
            
            return ['status' => 'success'];
        /*} catch (\Exception $e) {
            // Handle error (e.g., API rate limit exceeded, network error)
            return [
                'status' => 'error',
                'message' => 'An error occurred while checking your droplet limit. Please try again later.'
            ];
        }*/
    }


    /**
     * this function gets your sshkey ifyou have one or 
     * help you create one if you don't.
     */
    private function getOrCreateSSHKey($apiToken)
    {
        $client = new Client();
        $sshKeyName = 'Default SSH Key for Users';
        $publicKey = $this->ssh_key_in_session;
        //dd($publicKey);
        //$publicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQCzp/ztFkVR4oF/QBhuHfwcVr8coqr8kGAv35WdjoM0EIAWETNWCDbLQkljI4HtO2RnAIyxt6mSeyHmg4++9CdVv5uenQ5sSdx1BStZWH3XhuT6pJ+OTqavP8qehgQSZUQ2+uBkahMSxMpVr7Av/LM7a/dLS2j58sta8ywabwhk9zPLqJ+dUpgHDdRfltlRiMG3GMKwWiU/HvEU3Ys4Pnbq+QrJasIE0x8TtE1yzAP1VKd8nTwVKi21yPVNz6zQjnEl1tAjVbUS3AzOtJt/6f/PucZUGH1eHMlM89uwSTc2UpKLuYDEGJle3Yv081EOqeAB3WR0GycNwvSL2LqVtUKoM4ohIXZJ0zxhlnwc7WHg8ZnI6zick8+j8/7XFi141mRzylZrIzcTH27Qsb3z2tizyZB5Kt7zYJ9eDnNAmn961knHcUF74cNgOdTRgMH8AGYabjdqngupuHBmBEiahMg9vrj2RP5PgksjDFnWKQrhaezFs+dp6Jv/aGfiZDhDpVM= victor@victor-Latitude-3380';
         
        // Check for existing SSH keys
        $response = $client->get('https://api.digitalocean.com/v2/account/keys', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],
        ]);

        $sshKeys = json_decode($response->getBody()->getContents(), true)['ssh_keys'];

        // Find an existing key with the same name
        foreach ($sshKeys as $key) {
            if ($key['public_key'] === $publicKey) {
                return $key['fingerprint'];
            }
        }

        // Add new SSH key if not found
        $response = $client->post('https://api.digitalocean.com/v2/account/keys', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $sshKeyName,
                'public_key' => $publicKey,
            ],
        ]);

        $newKey = json_decode($response->getBody()->getContents(), true);
        return $newKey['fingerprint'];
    }



    //all about CI/CD
   /* public function addWorkflowFileToRepo($githubToken, $repoOwner, $repoName, $workflowContent) {
        $filePath = '.github/workflows/deploy.yml';
        $commitMessage = "Add CI/CD workflow file for deployment";
    
        $url = "https://api.github.com/repos/$repoOwner/$repoName/contents/$filePath";
    
        $data = [
            "message" => $commitMessage,
            "content" => base64_encode($workflowContent), // GitHub API expects base64 encoding
            "branch" => "master",
        ];
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: token $githubToken",
            "Accept: application/vnd.github.v3+json",
            "User-Agent: AquaPush-App"
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return $response ? json_decode($response, true) : false;
    }*/
   

}
