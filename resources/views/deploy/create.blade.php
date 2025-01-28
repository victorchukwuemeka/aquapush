@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-3xl font-bold text-red-600 mb-8 text-center">
        Deploy a New Application
    </h1>

    <p class="text-gray-700 mb-10 text-center">
        Provide the necessary details below to set up and deploy your Laravel application
         to DigitalOcean.
        <p>
            <a href="{{ route('get-ssh') }}" class="text-red-600 hover:underline">
                How to get your SSH key
            </a>
        </p>
    </p>

    <form action="{{ route('deploy.store') }}" method="POST" class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        @csrf

        <!-- DigitalOcean API Token -->
        <div class="mb-6">
            <label for="api_token" class="block text-gray-700 font-medium mb-2">DigitalOcean API Token</label>
            <input
                type="text"
                id="api_token"
                name="api_token"
                value="{{ old('api_token') }}"
                placeholder="Enter your API token"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-600 focus:border-red-600"
                required
            >
        </div>

        <!-- SSH Public Key (Paste) -->
        <div class="mb-6">
            <label for="ssh_key" class="block text-gray-700 font-medium mb-2">Paste Your SSH Public Key</label>
            <textarea
                id="ssh_key"
                name="ssh_key"
                rows="5"
                placeholder="Paste your SSH public key here (e.g., ssh-rsa AAAAB3NzaC1yc2E...)"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-600 focus:border-red-600"
                required
            >{{ old('ssh_key') }}</textarea>
            <p class="text-sm text-gray-500 mt-2">
                Don't have an SSH key? <a href="{{ route('get-ssh') }}" class="text-red-600 hover:underline">Learn how to generate one</a>.
            </p>
        </div>
        

        <!-- DigitalOcean Region -->
        <div class="mb-6">
            <label for="region" class="block text-gray-700 font-medium mb-2">Select Region</label>
            <select name="region" id="region" required class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-600 focus:border-red-600">
                <option value="">-- Select Region --</option>
                @foreach($regions as $region => $description)
                    <option value="{{ $region }}">{{ $description }}</option>
                @endforeach
            </select>
        </div>

        <!-- DigitalOcean Droplet Size -->
        <div class="mb-6">
            <label for="droplet_size" class="block text-gray-700 font-medium mb-2">Select Droplet Size</label>
            <select name="droplet_size" id="droplet_size" required class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-600 focus:border-red-600">
                <option value="">-- Select Droplet Size --</option>
                @foreach($dropletSizes as $size => $description)
                    <option value="{{ $size }}">{{ $description }}</option>
                @endforeach
            </select>
        </div>

        <!-- Droplet Name -->
        <div class="mb-6">
            <label for="droplet_name" class="block text-gray-700 font-medium mb-2">Droplet Name</label>
            <input
                type="text"
                id="droplet_name"
                name="droplet_name"
                placeholder="e.g., my-laravel-app"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-600 focus:border-red-600"
                required
            >
        </div>

        <!-- Desired Image -->
        <div class="mb-6">
            <label for="image" class="block text-gray-700 font-medium mb-2">Select Desired Image</label>
            <select name="image" id="image" required class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-600 focus:border-red-600">
                <option value="">-- Select Image --</option>
                @foreach($images as $imageId => $imageDescription)
                    <option value="{{ $imageId }}">{{ $imageDescription }}</option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-8">
            <button
                type="submit"
                class="bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out shadow-md"
            >
                Deploy Now
            </button>
        </div>
    </form>

    <!-- Deployment Status -->
    <div id="status-container" class="mt-10 max-w-3xl mx-auto p-6 border rounded-lg shadow-md bg-gray-50">
        <h2 class="font-bold text-xl text-red-600 mb-4 text-center">Deployment Status</h2>
        <p id="deployment-status" class="text-gray-700 text-center">Waiting for updates...</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/pusher-js"></script>
<script>
    const userId = {{ auth()->id() }};
    Echo.private(`deployments.${userId}`)
        .listen('DeploymentStatusUpdated', (e) => {
            document.getElementById('deployment-status').innerText = `Status: ${e.status}`;
        });
</script>
@endsection