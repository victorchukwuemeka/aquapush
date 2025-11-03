<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DigitalOceanDroplet;
use Illuminate\Support\Facades\Http;

class DigitalOceanLaravelProjectController extends Controller
{
    public function configure_laravel_project_form($droplet_id){
         
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

         return view('dashboard.droplets.laravelApps.configure-laravel-apps', [
            'droplet' => $droplet,
         ]);
         
         /*return view('dashboard.droplets.configure', [
            'droplet' => $droplet,
            'phpVersions' => $phpVersions,
            'webServers' => $webServers
        ]);*/
    }


     // Add GitHub repository to droplet
     public function add_repo_to_droplet(Request $request)
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

          //dd($response->status(), $response->body());



         if ($response->failed()) {
             return response()->json(['error' => 'Failed to add repository'], 500);
         }
  
         return response()->json($response->json());
     }


}
