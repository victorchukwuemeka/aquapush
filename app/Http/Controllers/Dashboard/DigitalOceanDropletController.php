<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use App\Models\DigitalOceanDroplet;
use Illuminate\Container\Attributes\Log as AttributesLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Events\DeploymentStatusUpdated;
use App\Services\CloudInitService;


class  DigitalOceanDropletController extends Controller
{   
    /** all variable needed by the droplets for housekeeping  */
    private $store_droplet_data;

    private $ssh_key_in_session;

    private $ip_address;

    private $client;

    protected $cloud_init_service;
    
    /**adding cloud init throught a constructor to our droplet */
    public function __construct(CloudInitService $cloudInitService){
        $this->cloud_init_service = $cloudInitService;
        $this->client = new Client();
    }


    /** i checked this sizes from digitalOcean if it changes we will make amendments  */
    private $dropletSizes = [
        's-1vcpu-1gb' => 'Basic: 1 vCPU, 1 GB RAM',
        's-1vcpu-2gb' => 'Standard: 1 vCPU, 2 GB RAM',
        's-2vcpu-2gb' => 'Standard: 2 vCPU, 2 GB RAM',
        's-2vcpu-4gb' => 'Standard: 2 vCPU, 4 GB RAM',
        's-2vcpu-8gb' => 'Standard: 2 vCPU, 8 GB RAM',
        
    ];

    /**  region they support  */
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
     
    /**takes you to the page where you fill the form for droplet creation */
    public function form_for_droplet_creation():View
    {
        $api_token = session('digitalocean.api_token');
        $droplet_size = session('digitalocean.droplet_size');
        
        return view('dashboard.droplets.digitalocean-droplet-form', [
            'dropletSizes' => $this->dropletSizes,
            'regions' => $this->regions,
            'images' => $this->images,
            'api_token' => $api_token,
            'droplet_size' => $droplet_size,
        ]);
    }



    /** handles the calling of the needed fuction for droplet creation */
    public function droplet_creation_composer(Request $request){
        //try {
            //dd($request);
            // Validate the incoming request data
            $validatedData = $this->validate_droplet_data($request);
            //dd($validatedData);
           // Store the token and droplet size in session  as needed
           // For demonstration, we're using the session
           session([
            'digitalocean.api_token' => $validatedData['api_token'],
            'digitalocean.droplet_size' => $validatedData['droplet_size'],
          ]);

         // Check droplet limit so you will not push more droplet.
         $dropletLimitCheck = $this->check_droplet_limit($validatedData['api_token']);
         //dd($dropletLimitCheck);
         if ($dropletLimitCheck['status'] === 'error') {
             return redirect()->route('digitalocean.config')->with('error', $dropletLimitCheck['message']);
         }

         $sshFingerprint = $this->ssh_key_in_session = $validatedData['ssh_key'];
         //dd($sshFingerprint);
         $sshFingerprint = $this->sanitize_and_validateSShKey($sshFingerprint,$validatedData['api_token']);
         
         
         //dd($validatedData['image']);
         //creating the droplet
         $returned_droplet_id = $this->create_droplet(
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
            // First, store the droplet's data in our database.
            $this->store_droplet_data_on_our_db($validatedData, $returned_droplet_id);

            // Now, redirect the user to the deployment status page.
            return redirect()->route('droplets.deployment.status', ['droplet_id' => $returned_droplet_id]);
          } else {
            // If droplet creation failed, show an error view.
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
    private function store_droplet_data_on_our_db(array $validatedData, mixed $droplet_id)
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
            //return redirect()->route('dashboard')->with('success', 'Droplet created successfully.');
        }else{
         return 'empty ip';   
        }
    }
    

    
    /** the actual creation of the droplet on digitalOcean */
    public function create_droplet($apiToken, $dropletName, $region, $size, $image, $publicKey = null)
    {  
        //dd($publicKey);
        //dd($apiToken);
        $sshFingerprint = null;

        // Check or add SSH key only if a public key is provided
        if ($publicKey) {
            $sshFingerprint = $this->get_or_add_ssh_key($apiToken, $publicKey);
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
        $this->ip_address = $this->get_droplet_ip_address($droplet_id, $apiToken);
        
        //dd($this->ip_address);
        //dd($responseArray['droplet']['id']);
        if (isset($responseArray['droplet']['id'])) {
            return $responseArray['droplet']['id'];
        }else {
            return null;
        }
    }


       /**
     * this function is for getting the digitalocean droplet  ip address.
     */
    public function get_droplet_ip_address($dropletId, $apiToken){
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
     * Check if an SSH key exists. If not, add it.
     */
    public function get_or_add_ssh_key($apiToken, $publicKey)
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
                return $key['fingerprint']; 
            }
        }

        
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
        return $newKey['fingerprint']; 
    }


    private function sanitize_and_validateSShKey($ssh_key, $api_token){
        $ssh_key = trim($ssh_key);
        $ssh_key = preg_replace('/\s+/', ' ',$ssh_key);
        return $ssh_key;
    }

    /**
     * function used for validating the request, from the 
     * droplet creation  form.
     */
    private function validate_droplet_data($data){
        return $data->validate([
            'api_token' => 'required|string',
            'ssh_key' => 'required|string',
            'region' => 'required|string',
            'droplet_size' => 'required|string',
            'droplet_name' => 'required|string',
            'image' => 'required|string',
            
        ]);
    }


      //handling the amount of droplet in digitalOcean 
   private function check_droplet_limit($apiToken)
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

   
    /**
     * shows all the droplet a user has in he's dashboard 
     */
    public function index(){

        
        
        try {
            $user_id = Auth::id();
            $droplets = DigitalOceanDroplet::where('user_id', $user_id)->get();
            return view('dashboard.droplets.index-droplet', ['droplets' => $droplets]);
        } catch (\Throwable $th) {
            return redirect()->back()
            ->with('error', 'Failed to fetch droplets: ' . $th->getMessage());
        }
    }

    
    /**
     * getting a particula  droplpet more like show me this drolet data 
     */
    public function get_droplet($droplet_id){
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

    
    
    /**
     * handles the deleting of the entire droplet not a project or app 
     */
    public function deleteDroplet($droplet_id):View|RedirectResponse
    {
          $droplet = DigitalOceanDroplet::where('droplet_id', $droplet_id)->first();
 
          if (!$droplet) {
            return view('dashboard.droplets.error', [
                'message' => 'Droplet not found',
                'status' => 404
            ]);  
          }
 
          try {
              $response = Http::withHeaders([
                  'Authorization' => "Bearer {$droplet->api_token}",
              ])->delete("https://api.digitalocean.com/v2/droplets/{$droplet_id}");
 
              if ($response->successful()) {
                  $droplet->delete();
                  return view('dashboard.droplets.success', [
                    'message' => 'Droplet deleted successfully'
                  ]);
              }
              
              return redirect()->route('droplets.index')
              ->with('success', 'Droplet deleted successfully.');
          } catch (\Exception $e) {
            return view('dashboard.droplets.error', [
                'message' => 'An unexpected error occurred while deleting the droplet.',
                'details' => $e->getMessage(),
                'status' => 500
            ]);
          }
    }



    public function getDropletStatus($droplet_id)
    {
        $droplet = DigitalOceanDroplet::where('droplet_id', $droplet_id)->first();

        if (!$droplet) {
            return response()->json(['status' => 'error', 'message' => 'Droplet not found.'], 404);
        }

        // If IP address is missing, try to fetch it from DigitalOcean
        if (!$droplet->ip_address) {
            // Re-use the existing method to get the IP from the API
            $ip = $this->get_droplet_ip_address($droplet->droplet_id, $droplet->api_token);

            if ($ip) {
                $droplet->ip_address = $ip;
                $droplet->save();
            } else {
                // If there's still no IP, the droplet is likely still booting.
                // We send a 'pending' status to let the frontend know to try again.
                return response()->json(['status' => 'pending', 'message' => 'Droplet is being created, but no IP address is available yet.']);
            }
        }

        // Now that we are reasonably sure we have an IP, try to fetch the status file
        try {
            $response = Http::timeout(5)->get("http://{$droplet->ip_address}/droplet_status.txt");

            if ($response->successful()) {
                return response()->json(['status' => 'success', 'message' => $response->body()]);
            }

            // If the file is not found (404), the web server might not be ready.
            if ($response->notFound()) {
                return response()->json(['status' => 'pending', 'message' => 'Droplet is running, but the web server is not yet responding.']);
            }

            return response()->json(['status' => 'error', 'message' => 'Failed to fetch status file from droplet.'], 500);

        } catch (\Exception $e) {
            // A connection exception likely means the droplet is not yet reachable.
            return response()->json(['status' => 'pending', 'message' => 'Waiting for droplet to become reachable...']);
        }
    }
    
    public function showDeploymentStatus($droplet_id){
        return view('dashboard.droplets.deployment-status',['droplet_id' => $droplet_id]);
    }


}



