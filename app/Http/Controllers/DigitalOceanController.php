<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class DigitalOceanController extends Controller
{
    private $dropletSizes = [
        's-1vcpu-1gb' => 'Basic: 1 vCPU, 1 GB RAM',
        's-1vcpu-2gb' => 'Standard: 1 vCPU, 2 GB RAM',
        's-2vcpu-2gb' => 'Standard: 2 vCPU, 2 GB RAM',
        's-2vcpu-4gb' => 'Standard: 2 vCPU, 4 GB RAM',
        's-2vcpu-8gb' => 'Standard: 2 vCPU, 8 GB RAM',
        // Add more sizes as needed
    ];

    public function showForm()
    {
        $api_token = session('digitalocean.api_token');
        $droplet_size = session('digitalocean.droplet_size');
        
        return view('dashboard.digitalOcean-config', [
            'dropletSizes' => $this->dropletSizes,
            'api_token' => $api_token,
            'droplet_size' => $droplet_size,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'api_token' => 'required|string',
            'droplet_size' => 'required|string',
        ]);
         
        //dd($validatedData);

        // Store the token and droplet size in session or database as needed
        // For demonstration, we're using the session
        session([
            'digitalocean.api_token' => $validatedData['api_token'],
            'digitalocean.droplet_size' => $validatedData['droplet_size'],
        ]);

        // Check droplet limit
        $dropletLimitCheck = $this->checkDropletLimit($validatedData['api_token']);
        if ($dropletLimitCheck['status'] === 'error') {
            return redirect()->route('digitalocean.config')->with('error', $dropletLimitCheck['message']);
        }
        //$ssh_key = 
        //'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQCzp/ztFkVR4oF/QBhuHfwcVr8coqr8kGAv35WdjoM0EIAWETNWCDbLQkljI4HtO2RnAIyxt6mSeyHmg4++9CdVv5uenQ5sSdx1BStZWH3XhuT6pJ+OTqavP8qehgQSZUQ2+uBkahMSxMpVr7Av/LM7a/dLS2j58sta8ywabwhk9zPLqJ+dUpgHDdRfltlRiMG3GMKwWiU/HvEU3Ys4Pnbq+QrJasIE0x8TtE1yzAP1VKd8nTwVKi21yPVNz6zQjnEl1tAjVbUS3AzOtJt/6f/PucZUGH1eHMlM89uwSTc2UpKLuYDEGJle3Yv081EOqeAB3WR0GycNwvSL2LqVtUKoM4ohIXZJ0zxhlnwc7WHg8ZnI6zick8+j8/7XFi141mRzylZrIzcTH27Qsb3z2tizyZB5Kt7zYJ9eDnNAmn961knHcUF74cNgOdTRgMH8AGYabjdqngupuHBmBEiahMg9vrj2RP5PgksjDFnWKQrhaezFs+dp6Jv/aGfiZDhDpVM= victor@victor-Latitude-3380';
        

         // Check if the SSH key exists or add it
         $sshFingerprint = $this->getOrCreateSSHKey($validatedData['api_token']);


        // Call the method to create the droplet
        $droplet = $this->createDroplet(
            $validatedData['api_token'],
            'my-droplet',  // Customize droplet name
            'nyc1',        // Customize region
            $validatedData['droplet_size'],
            'ubuntu-20-04-x64', // Use the desired image
            'victorchukwuemeka/aquapush',  // Replace with the actual GitHub repo
            $sshFingerprint
        );

        //dd($droplet);
        return redirect()->route('dashboard')->with('success', 'Droplet created successfully!');
    }

    public function createDroplet($apiToken, $dropletName, $region, $size, $image, $githubRepo,$ssh_key)
    {
        $client = new Client();
         
        //dd($githubRepo);
        //dd($this->getUserData($githubRepo));

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
                'user_data' => $this->getUserData($githubRepo),
            ],
        ]);
        
        //dd($response);
        $body = json_decode($response->getBody()->getContents(), true);
        return $body;
    }
    
    private function getUserData($githubRepo)
    {
        // Custom user data script to clone the GitHub repo and set up Laravel with Apache2
        return "#!/bin/bash
            apt-get update -y
            apt-get install -y git
            apt-get install -y apache2
            apt-get install -y php libapache2-mod-php php-mbstring php-xml php-bcmath php-cli php-curl php-zip php-mysql
            cd /var/www/html
            git clone https://github.com/$githubRepo  
            composer install
            cp .env.example .env
            php artisan key:generate
            php artisan migrate
            chown -R www-data:www-data /var/www/html
            chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
            systemctl enable apache2
            systemctl restart apache2";
    }


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


    private function getOrCreateSSHKey($apiToken)
    {
        $client = new Client();
        $sshKeyName = 'Default SSH Key for Users';
        $publicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQCzp/ztFkVR4oF/QBhuHfwcVr8coqr8kGAv35WdjoM0EIAWETNWCDbLQkljI4HtO2RnAIyxt6mSeyHmg4++9CdVv5uenQ5sSdx1BStZWH3XhuT6pJ+OTqavP8qehgQSZUQ2+uBkahMSxMpVr7Av/LM7a/dLS2j58sta8ywabwhk9zPLqJ+dUpgHDdRfltlRiMG3GMKwWiU/HvEU3Ys4Pnbq+QrJasIE0x8TtE1yzAP1VKd8nTwVKi21yPVNz6zQjnEl1tAjVbUS3AzOtJt/6f/PucZUGH1eHMlM89uwSTc2UpKLuYDEGJle3Yv081EOqeAB3WR0GycNwvSL2LqVtUKoM4ohIXZJ0zxhlnwc7WHg8ZnI6zick8+j8/7XFi141mRzylZrIzcTH27Qsb3z2tizyZB5Kt7zYJ9eDnNAmn961knHcUF74cNgOdTRgMH8AGYabjdqngupuHBmBEiahMg9vrj2RP5PgksjDFnWKQrhaezFs+dp6Jv/aGfiZDhDpVM= victor@victor-Latitude-3380';

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

    public function addWorkflowFileToRepo($githubToken, $repoOwner, $repoName, $workflowContent) {
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
    }
    
}
