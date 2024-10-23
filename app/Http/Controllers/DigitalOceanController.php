<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        return view('dashboard.digitalOcean-config', ['dropletSizes' => $this->dropletSizes]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'api_token' => 'required|string',
            'droplet_size' => 'required|string',
        ]);

        // Store the token and droplet size in session or database as needed
        // For demonstration, we're using the session
        session([
            'digitalocean.api_token' => $validatedData['api_token'],
            'digitalocean.droplet_size' => $validatedData['droplet_size'],
        ]);

        return redirect()->route('digitalOcean.config')->with('success', 'DigitalOcean configuration saved successfully.');
    }
}
