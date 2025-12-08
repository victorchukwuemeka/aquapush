<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DigitalOceanDroplet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


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
     public function add_repo_to_droplet(Request $request){
        try {

            // 1. Check billing first
            /*if (!auth()->user()->is_subscribed) {
                return redirect()
                ->route('billing.show')
                ->with('error', 'Please subscribe before deploying your repository.');
            }*/
            
            //i have to sanitized what am adding
            $input = $request->only([
                "droplet_ip",
                "repo_url",
                "db_name",
                "db_user",
                "db_password"
            ]);
            $input = array_map('trim', $input);
            $input = array_map('htmlspecialchars', $input);

            $api_key = 'YOUR_SECRET_KEY';

            $response = Http::withHeaders([
                'X-API-KEY' => $api_key,
            ])->post("http://{$request->droplet_ip}/install.php", [
                'repo' => $request->repo_url,
                'db_name' => $request->db_name,      
                'db_user' => $request->db_user,      
                'db_pass' => $request->db_pass,
            ]);
            
            if ($response->failed()) {
                return redirect()
                ->back()
                ->with(
                    'error', 'Failed to add repository.
                     Please check your droplet or API.'
                );
            }
            return redirect()
            ->route('droplets.index')
            ->with('success', 'Repository added successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
            ->back()->withErrors($e->errors())
            ->withInput();
        } catch(\Exception  $e){
            Log::error('Droplet API error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            ]);
        }
     }

}
