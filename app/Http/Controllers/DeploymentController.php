<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class DeploymentController extends Controller
{
    public function showDashboard()
    {
        return view('dashboard.index');
    }

    public function fetchRepositoryDetails(Request $request)
    {  
        //dd($request);
        
        $request->validate([
            'repository' => 'required|string',
        ]);
        
        // get the repository which is the user {owner}/{repo_name} 
        $repository = $request->input('repository');

        //$user = Auth::user();
        //dd($user);
        
        //dd(env('GITHUB_TOKEN'));
        //dd($repository);
        // Fetch repository details from GitHub
        $response = Http::withToken(env('GITHUB_TOKEN'))
            ->get("https://api.github.com/repos/{$repository}");
        //dd($response);

        if ($response->successful()) {
            $repoData = $response->json();
            // Proceed to deploy the application
            //$this->deployToDigitalOcean($repoData);

            return view('dashboard.repository-details', compact('repoData'));
        } else {
            return back()->withErrors(['msg' => 'Failed to fetch repository details']);
        }
    }

    protected function deployToDigitalOcean($repoData)
    {
        // Deploy to DigitalOcean using API
        $response = Http::withToken(env('DIGITALOCEAN_TOKEN'))
            ->post('https://api.digitalocean.com/v2/droplets', [
                'name' => $repoData['name'],
                'region' => 'nyc3',  // Specify your region
                'size' => 's-1vcpu-1gb',  // Choose droplet size
                'image' => 'ubuntu-20-04-x64',  // Specify the image
                'ssh_keys' => [env('SSH_KEY_ID')],  // Add your SSH key
                'backups' => false,
                'ipv6' => true,
                // 'user_data' => $yourScriptToCloneRepoAndSetupLaravel,  // Optional script
            ]);

        // Check response and handle accordingly
        if ($response->successful()) {
            // Handle successful deployment
        } else {
            // Handle error
        }
    }
}
