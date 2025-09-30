<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use App\Models\DigitalOceanDroplet;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Http;



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
         //dd($droplet->ip_address);
         // Fetch user's connected repositories
         //$repositories = auth()->user()->connectedRepositories;
         
         // Fetch server capabilities
         //$phpVersions = ['7.4', '8.0', '8.1', '8.2'];
         //$webServers = ['nginx', 'apache'];


         $droplet_ip = $droplet->ip_address;

         return view('dashboard.droplets.configure', [
            'droplet' => $droplet,
         ]);
         
         /*return view('dashboard.droplets.configure', [
            'droplet' => $droplet,
            'phpVersions' => $phpVersions,
            'webServers' => $webServers
        ]);*/
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

         $api_key = 'YOUR_SECRET_KEY';
 
         $response = Http::withHeaders([
             'X-API-KEY' => $api_key, // Use X-API-KEY header
         ])->post("http://{$request->droplet_ip}/install.php", [
             'repo' => $request->repo_url,
             'db_name' => $request->db_name,      
             'db_user' => $request->db_user,      
             'db_pass' => $request->db_pass,
         ]);
         
          //log the error 
          /*Log::error("droplet api response", [
            'status' => $response->status(),
            'body' => $response->body(),
            'headers' => $response->headers(),
          ]);*/

          dd($response->status(), $response->body());



         if ($response->failed()) {
             return response()->json(['error' => 'Failed to add repository'], 500);
         }
  
         return response()->json($response->json());
     }

     
   
      // Delete Droplet
      public function deleteDroplet($droplet_id)
      {
          $droplet = DigitalOceanDroplet::where('droplet_id', $droplet_id)->first();
 
          if (!$droplet) {
              return response()->json(['error' => 'Droplet not found'], 404);
          }
 
          try {
              $response = Http::withHeaders([
                  'Authorization' => "Bearer {$droplet->api_token}",
              ])->delete("https://api.digitalocean.com/v2/droplets/{$droplet_id}");
 
              if ($response->successful()) {
                  $droplet->delete(); // Remove from your database
                  return response()->json(['message' => 'Droplet deleted successfully']);
              }
 
              return response()->json(['error' => 'Failed to delete droplet', 'details' => $response->json()], $response->status());
          } catch (\Exception $e) {
              return response()->json(['error' => 'An error occurred', 'details' => $e->getMessage()], 500);
          }
      }

}



