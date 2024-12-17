@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-3xl font-bold text-red-600 mb-6">Deploy a New Application</h1>
    <p class="text-gray-700 mb-8">Fill in the details below to set up and deploy your Laravel application to DigitalOcean.</p>

    <form action="{{ url('deployments.store') }}" method="POST">
        @csrf
        <!-- GitHub Repository -->
        <div class="mb-6">
            <label for="repository" class="block text-gray-700 font-medium mb-2">GitHub Repository URL</label>
            <input
                type="url"
                id="repository"
                name="repository"
                placeholder="https://github.com/username/repository"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-600 focus:border-red-600"
                required
            >
        </div>

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

        <!-- Droplet Size Selection -->
        <div class="mb-6">
            <label for="droplet_size" class="block text-gray-700 font-medium mb-2">Select Droplet Size</label>
            <select
                id="droplet_size"
                name="droplet_size"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-red-600 focus:border-red-600"
                required
            >
                <option value="s-1vcpu-1gb">Basic (1 vCPU, 1GB RAM)</option>
                <option value="s-2vcpu-2gb">Standard (2 vCPUs, 2GB RAM)</option>
                <option value="s-4vcpu-8gb">Pro (4 vCPUs, 8GB RAM)</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button
                type="submit"
                class="bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out shadow-md"
            >
                Deploy Now
            </button>
        </div>
    </form>
</div>
@endsection

