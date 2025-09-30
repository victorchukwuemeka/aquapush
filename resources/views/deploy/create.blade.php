@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="text-center mb-10 animate-fade-in">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-red-600 mb-4 tracking-tight">
            Deploy a New Application
        </h1>
        <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
            Set up and deploy your Laravel application to DigitalOcean with ease.
            <a href="{{ route('get-ssh') }}" class="text-red-600 hover:underline font-medium">
                Learn how to get your SSH key
            </a>.
        </p>
    </div>

    <!-- Form -->
    <form action="{{ route('deploy.store') }}" method="POST" class="max-w-3xl mx-auto bg-white p-6 sm:p-8 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
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
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                required
                aria-describedby="api_token_error"
            >
            @error('api_token')
                <p id="api_token_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- SSH Public Key -->
        <div class="mb-6">
            <label for="ssh_key" class="block text-gray-700 font-medium mb-2">Paste Your SSH Public Key</label>
            <textarea
                id="ssh_key"
                name="ssh_key"
                rows="5"
                placeholder="e.g., ssh-rsa AAAAB3NzaC1yc2E..."
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                required
                aria-describedby="ssh_key_error"
            >{{ old('ssh_key') }}</textarea>
            <p class="text-sm text-gray-500 mt-2">
                Need an SSH key? <a href="{{ route('get-ssh') }}" class="text-red-600 hover:underline">Learn how to generate one</a>.
            </p>
            @error('ssh_key')
                <p id="ssh_key_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- DigitalOcean Region -->
        <div class="mb-6">
            <label for="region" class="block text-gray-700 font-medium mb-2">Select Region</label>
            <select
                name="region"
                id="region"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                aria-describedby="region_error"
            >
                <option value="">-- Select Region --</option>
                @foreach($regions as $region => $description)
                    <option value="{{ $region }}" {{ old('region') == $region ? 'selected' : '' }}>{{ $description }}</option>
                @endforeach
            </select>
            @error('region')
                <p id="region_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- DigitalOcean Droplet Size -->
        <div class="mb-6">
            <label for="droplet_size" class="block text-gray-700 font-medium mb-2">Select Droplet Size</label>
            <select
                name="droplet_size"
                id="droplet_size"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                aria-describedby="droplet_size_error"
            >
                <option value="">-- Select Droplet Size --</option>
                @foreach($dropletSizes as $size => $description)
                    <option value="{{ $size }}" {{ old('droplet_size') == $size ? 'selected' : '' }}>{{ $description }}</option>
                @endforeach
            </select>
            @error('droplet_size')
                <p id="droplet_size_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Droplet Name -->
        <div class="mb-6">
            <label for="droplet_name" class="block text-gray-700 font-medium mb-2">Droplet Name</label>
            <input
                type="text"
                id="droplet_name"
                name="droplet_name"
                value="{{ old('droplet_name') }}"
                placeholder="e.g., my-laravel-app"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                required
                aria-describedby="droplet_name_error"
            >
            @error('droplet_name')
                <p id="droplet_name_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Desired Image -->
        <div class="mb-6">
            <label for="image" class="block text-gray-700 font-medium mb-2">Select Desired Image</label>
            <select
                name="image"
                id="image"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                aria-describedby="image_error"
            >
                <option value="">-- Select Image --</option>
                @foreach($images as $imageId => $imageDescription)
                    <option value="{{ $imageId }}" {{ old('image') == $imageId ? 'selected' : '' }}>{{ $imageDescription }}</option>
                @endforeach
            </select>
            @error('image')
                <p id="image_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-8">
            <button
                type="submit"
                class="bg-red-600 text-white py-3 px-8 rounded-lg hover:bg-red-700 transition-colors duration-300 shadow-md text-base sm:text-lg font-medium"
            >
                Deploy Now
            </button>
        </div>
    </form>

    <!-- Deployment Status -->
    <div id="status-container" class="mt-10 max-w-3xl mx-auto p-6 rounded-xl bg-gray-50 border border-gray-200 shadow-sm">
        <h2 class="text-xl font-semibold text-red-600 mb-4 text-center">Deployment Status</h2>
        <p id="deployment-status" class="text-gray-700 text-center text-base">Waiting for updates...</p>
    </div>
</div>

<!-- JavaScript for Real-Time Deployment Status -->
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0-rc2/dist/web/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userId = {{ auth()->id() }};
        Echo.private(`deployments.${userId}`)
            .listen('DeploymentStatusUpdated', (e) => {
                const statusElement = document.getElementById('deployment-status');
                statusElement.innerText = `Status: ${e.status}`;
                statusElement.classList.add('animate-pulse');
                setTimeout(() => statusElement.classList.remove('animate-pulse'), 1000);
            });
    });
</script>

<!-- Custom CSS for Animation -->
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection