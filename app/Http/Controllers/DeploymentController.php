<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\DigitalOceanDroplet;
use App\Events\DeploymentStatusUpdated;
use Illuminate\Support\Facades\Log;
use App\Services\CloudInitService;


class DeploymentController extends Controller
{   
    
    //variable for deployment storage
    private $store_deployment;

    private $ssh_key_in_session;

    private $ip_address;

    private $client;

    protected $cloud_init_service;

    public function __construct(CloudInitService $cloudInitService){
        $this->cloud_init_service = $cloudInitService;
        $this->client = new Client();
    }

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
        'ubuntu-22-04-x64' => 'Ubuntu 22.04 (x64)',
        'rockylinux-8-x64' => 'Rocky Linux 8 (x64)',
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
        //try {
            //dd($request);
            // Validate the incoming request data
            $validatedData = $this->validateDeploymentData($request);
             //dd($validatedData);
           // Store the token and droplet size in session  as needed
           // For demonstration, we're using the session
           session([
            'digitalocean.api_token' => $validatedData['api_token'],
            'digitalocean.droplet_size' => $validatedData['droplet_size'],
          ]);

         // Check droplet limit so you will not push more droplet.
         $dropletLimitCheck = $this->checkDropletLimit($validatedData['api_token']);
         //dd($dropletLimitCheck);
         if ($dropletLimitCheck['status'] === 'error') {
             return redirect()->route('digitalocean.config')->with('error', $dropletLimitCheck['message']);
         }

         $sshFingerprint = $this->ssh_key_in_session = $validatedData['ssh_key'];
         //dd($sshFingerprint);
         $sshFingerprint = $this->sanitizeAndVaidateSShKey($sshFingerprint,$validatedData['api_token']);
         
         
         //dd($validatedData['image']);
         //creating the droplet
         $returned_droplet_id = $this->createDroplet(
            $validatedData['api_token'],
            $validatedData['droplet_name'], 
            $validatedData['region'],
            $validatedData['droplet_size'],
            $validatedData['image'], 
            $sshFingerprint
         );
         //dd($returned_droplet_id);

           // Step 6: Return success or failure
          if ($returned_droplet_id) {
            return  $this->storeDeployment($validatedData,$returned_droplet_id);
          } else {
            return view('dashboard.errors.deployment-error')->with('error', 'Failed to create droplet.');
          }
       // } catch (\Throwable $th) {
            //throw $th;
            // Handle exceptions and log errors
       //     Log::error('Droplet Deployment Error: ' . $th->getMessage());
       //     return view('dashboard.errors.deployment-error')->with('error', 'An error occurred while deploying the droplet.');
        //}
            //return redirect()->route('error-not-laravel');
        
    }
    

    
    /**
     * function used for storing the deployment in our database.
     */
    private function storeDeployment(array $validatedData, mixed $droplet_id)
    {   
        //dd($validatedData['api_token']);
        //dd($droplet_id);
        if ($this->ip_address) {
            DigitalOceanDroplet::create([
                'user_id' => Auth::user()->id,
                'api_token' => $validatedData['api_token'],
                'droplet_id' => $droplet_id,
                'ip_address' => $this->ip_address,
    
                //'droplet_size' => $validatedData['droplet_size'],
                //'repository' => $validatedData['repository'],
                //'region' => $validatedData['region'],
                //'image' => $validatedData['image'],
                //'droplet_name' => $validatedData['droplet_name'],
                //'ip_address' => $this->ip_address,
                //'status' => 'pending',
                
    
            ]);
            return redirect()->route('dashboard')->with('success', 'Droplet created successfully.');
        }else{
         return 'empty ip';   
        }
    }
    
    
    /**
     * function used for validating the request, from the 
     * deployment form.
     */
    private function validateDeploymentData($data){
        return $data->validate([
            'api_token' => 'required|string',
            'ssh_key' => 'required|string',
            'region' => 'required|string',
            'droplet_size' => 'required|string',
            'droplet_name' => 'required|string',
            'image' => 'required|string',
            
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



    /**
     * Check if an SSH key exists. If not, add it.
     */
    public function getOrAddSshKey($apiToken, $publicKey)
    {  
        $keyName = "Default ssh";
        // Fetch existing SSH keys
        $response = $this->client->get('https://api.digitalocean.com/v2/account/keys', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
            ],
        ]);

        $existingKeys = json_decode($response->getBody(), true)['ssh_keys'];

        // Check if the provided SSH key already exists
        foreach ($existingKeys as $key) {
            if ($key['public_key'] === $publicKey) {
                return $key['fingerprint']; // Return fingerprint if key exists
            }
        }

        // Add the SSH key if it doesn't exist
        $response = $this->client->post('https://api.digitalocean.com/v2/account/keys', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $keyName,
                'public_key' => $publicKey,
            ],
        ]);

        $newKey = json_decode($response->getBody(), true);
        return $newKey['fingerprint']; // Return the new fingerprint
    }

    public function createDroplet($apiToken, $dropletName, $region, $size, $image, $publicKey = null)
    {  
        //dd($publicKey);
        //dd($apiToken);
        $sshFingerprint = null;

        // Check or add SSH key only if a public key is provided
        if ($publicKey) {
            $sshFingerprint = $this->getOrAddSshKey($apiToken, $publicKey);
            //dd($sshFingerprint);
        }

        $cloud_init_script = $this->cloud_init_service->generateCloudInitScript();
        //dd($cloud_init_script);

        // Prepare droplet creation payload
        $payload = [
            'name' => $dropletName,
            'region' => $region,
            'size' => $size,
            'image' => $image, // Example: 'ubuntu-20-04-x64'
            'backups' => false,
            'user_data' => $cloud_init_script,
        ];

        // Add SSH keys to the payload only if a fingerprint exists
        if ($sshFingerprint) {
            $payload['ssh_keys'] = [$sshFingerprint];
        }

        // Create the droplet
        $response = $this->client->post('https://api.digitalocean.com/v2/droplets', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);
        
                      
        //return the data of the droplet created.
        $responseArray =  json_decode($response->getBody(), true); 
        $droplet_id = $responseArray['droplet']['id'];
        
        //dd($apiToken);
        sleep(5);
        $this->ip_address = $this->getIpAddress($droplet_id, $apiToken);
        
        //dd($this->ip_address);
        //dd($responseArray['droplet']['id']);
        if (isset($responseArray['droplet']['id'])) {
            return $responseArray['droplet']['id'];
        }else {
            return null;
        }
    }
    

    private function sanitizeAndVaidateSShKey($ssh_key, $api_token){
        $ssh_key = trim($ssh_key);
        $ssh_key = preg_replace('/\s+/', ' ',$ssh_key);
        return $ssh_key;
    }

    
    



    /**
     * this function is for getting the ip address.
     */
    public function getIpAddress($dropletId, $apiToken){
        $max_attempt = 10;
        $attempt = 0;

        //dd($dropletId);
        //$client = new Client();
        while ($attempt < $max_attempt) {
            $response_for_get_call = $this->client->get("https://api.digitalocean.com/v2/droplets/{$dropletId}",[
                'headers'=>[
                    'Authorization' => 'Bearer '. $apiToken,
                    'Content-type' => 'application/json',
                ]
            ]);
            //dd($response_for_get_call);
            $body_from_get_call = json_decode($response_for_get_call->getBody()->getContents(), true);
            $networks = $body_from_get_call['droplet']['networks']['v4'];
            //dd($networks);
    
            foreach ($networks as $network) {
                if ($network['type'] === 'public') {
                    return $this->ip_address = $network['ip_address'];
                }
            }

            sleep(5);
            $attempt ++;
        }
        return 0;

    }

    /**
     * using the deployment status to monitor it .
     * 
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
  
 
    //handling the amount of droplet in digitalOcean 
   private function checkDropletLimit($apiToken)
   {    
        //dd($apiToken);
        //try {
            //$client = new Client();
            //dd($client);
            $response = $this->client->get('https://api.digitalocean.com/v2/account', [
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
            $dropletsResponse = $this->client->get('https://api.digitalocean.com/v2/droplets', [
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

    public function getDropletById($user_id, $droplet_id, $api_token){
        
        if (!$api_token) {
            return response()->json(['error' => 'DigitalOcean token not found'], 500);
        }

        $response = $this->client->get('https://api.digitalocean.com/v2/droplets/{$droplet_id}',[
           'headers' => [
                'Authorization' => 'Bearer ' . $api_token,
                'Content-Type' => 'application/json',
            ], 
        ]);

        $data = json_decode($response->getBody(), true);
        //dd($data);

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
