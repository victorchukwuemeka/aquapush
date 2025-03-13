<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Project;

class DigitalOceanController extends Controller
{
    // Fetch available droplets
    public function listDroplets(Request $request)
    {
        $request->validate([
            'api_token' => 'required|string',
        ]);

        $response = Http::withToken($request->api_token)
            ->get('https://api.digitalocean.com/v2/droplets');

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch droplets'], 500);
        }

        return response()->json($response->json());
    }

    // Create a new droplet
    public function createDroplet(Request $request)
    {
        $request->validate([
            'api_token' => 'required|string',
            'name' => 'required|string',
            'size' => 'required|string',
            'image' => 'required|string',
            'region' => 'required|string',
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::findOrFail($request->project_id);

        $response = Http::withToken($request->api_token)
            ->post('https://api.digitalocean.com/v2/droplets', [
                'name' => $request->name,
                'region' => $request->region,
                'size' => $request->size,
                'image' => $request->image,
                'user_data' => $this->generateDeploymentScript($project),
                'tags' => ['aquaPush'],
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to create droplet'], 500);
        }

        return response()->json($response->json());
    }

    // Generate deployment script for the droplet
    protected function generateDeploymentScript($project)
    {
        return "#!/bin/bash
        apt-get update && apt-get install -y git apache2 php libapache2-mod-php composer unzip

        # Clone the user's project
        git clone {$project->repo_url} /var/www/html/{$project->name}

        # Set permissions
        chown -R www-data:www-data /var/www/html/{$project->name}

        # Set up the Laravel project
        cd /var/www/html/{$project->name}
        composer install --no-interaction --prefer-dist
        cp .env.example .env
        php artisan key:generate

        # Enable and start Apache
        systemctl enable apache2 && systemctl start apache2

        # Ensure permissions are correct
        chown -R www-data:www-data /var/www/html/{$project->name}/storage /var/www/html/{$project->name}/bootstrap/cache
        chmod -R 775 /var/www/html/{$project->name}/storage /var/www/html/{$project->name}/bootstrap/cache
        ";
    }

    // Delete a droplet
    public function deleteDroplet(Request $request, $id)
    {
        $request->validate([
            'api_token' => 'required|string',
        ]);

        $response = Http::withToken($request->api_token)
            ->delete("https://api.digitalocean.com/v2/droplets/{$id}");

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to delete droplet'], 500);
        }

        return response()->json(['message' => 'Droplet deleted successfully']);
    }

    // Execute custom commands on a droplet
    public function executeCommand(Request $request, $id)
    {
        $request->validate([
            'api_token' => 'required|string',
            'command' => 'required|string',
        ]);

        $response = Http::withToken($request->api_token)
            ->post("https://api.digitalocean.com/v2/droplets/{$id}/actions", [
                'type' => 'run',
                'command' => $request->command,
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to execute command'], 500);
        }

        return response()->json($response->json());
    }
}
