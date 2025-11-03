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

class  DigitalOceanDropletController extends Controller
{   
   

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
    
    /** fetch the droplet we need to deploy our project to  */
    public function configureDeployment($droplet_id){
         
        try {
            $droplet = DigitalOceanDroplet::where('droplet_id', $droplet_id)->first();
            //dd(!$droplet);
            if (!$droplet) {
                return redirect()->back()->with('error', "Droplet with ID {$droplet_id} not found.");
            }
            
            if (empty($droplet->ip_address)) {
                return redirect()->back()
                ->with('error', "Droplet found but IP address is missing. Please verify droplet setup.");
            }
            
            return view('dashboard.droplets.configure', [
                'droplet' => $droplet,
            ]);

        } catch (\Throwable $th) {
             Log::error("Error configuring droplet {$droplet_id}: " . $th->getMessage());
             return redirect()->back()
             ->with('error', 'An unexpected error occurred while loading the droplet configuration.');
        }
         
    }




     // Add GitHub repository to droplet
     public function addRepoToDroplet(Request $request)
     {   
        
        // 1. Check billing first
        /*if (!auth()->user()->is_subscribed) {
            return redirect()
              ->route('billing.show')
              ->with('error', 'Please subscribe before deploying your repository.');
        }*/

         
         //dd($request->droplet_ip);
         $request->validate([
             'droplet_ip' => 'required|ip',
             'repo_url' => 'required|url',
             'db_name' => 'required|string',
             'db_user' => 'required|string', 
             'db_pass' => 'required|string',
         ]);

         //dd($request);
         $api_key = 'YOUR_SECRET_KEY';
         $dropletIp = $request->droplet_ip;
         $endpoint = "http://{$dropletIp}/install.php";
         
         try {
            $response = Http::withHeaders([
                'X-API-KEY' => $api_key,
            ])->timeout(60)->post($endpoint, [
                'repo' => $request->repo_url,
                'db_name' => $request->db_name,      
                'db_user' => $request->db_user,      
                'db_pass' => $request->db_pass,
            ]);

            /**
             * all the error logging .
             */
            Log::error("droplet api response", [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
            ]);

            if ($response->failed()) {
                Log::error("Droplet installation failed for {$dropletIp}", [
                    'error' => $response->body(),
                ]);

                return redirect()->back()
                  ->with('error', 'Deployment failed. Please verify the droplet and repository URL.');
                
            }

            return redirect()
            ->route('droplet.show', ['droplet_id' => $request->droplet_id])
            ->with('success', 'Repository successfully deployed to your droplet!');
            
         } catch (\Illuminate\Http\Client\RequestException $e) {
             Log::error("HTTP request error for droplet {$dropletIp}: " . $e->getMessage());
             return redirect()->back()->with('error', 'Network issue while connecting to droplet. Please try again.');
         }catch(\Exception $e){
             Log::error("Unexpected error deploying to droplet {$dropletIp}: " . $e->getMessage());
             return redirect()->back()->with('error', 'An unexpected error occurred during deployment.');
         }

     }

     
   
      // Delete Droplet
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

}



