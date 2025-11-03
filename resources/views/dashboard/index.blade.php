@extends('layouts.app')
@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar')
    
    <!-- Main Content -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <div class="container mx-auto max-w-7xl">
            <!-- Welcome Section -->
            <div class="text-center mb-12 animate-fade-in">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-red-600 mb-3 tracking-tight">
                    Welcome to Your AquaPush Dashboard  iiiiii
                </h1>
                <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
                    Seamlessly manage your deployments, track droplets, and optimize your AquaPush experience.
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 text-center">
                    <h3 class="text-gray-500 text-sm font-medium">Total Servers</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalServers ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 text-center">
                    <h3 class="text-gray-500 text-sm font-medium">Active Deployments</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $activeDeployments ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 text-center">
                    <h3 class="text-gray-500 text-sm font-medium">Last Activity</h3>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $lastActivity ?? 'Never' }}</p>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- My Servers & Deployments -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-red-600 mb-3">My Droplets & Deployments</h2>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">
                        View and manage all your servers, droplets, and deployment history.
                    </p>
                    <a href="{{ route('droplets.index') }}"
                       class="inline-block bg-red-600 text-white py-2 px-5 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm sm:text-base">
                        View Droplets
                    </a>
                </div>

                <!-- the box hanldes everything relating to droplet creation in the dashboard index  -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-green-600 mb-3">Create New Droplet</h2>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">
                        Provision a new DigitalOcean droplet configured for Laravel deployment.
                    </p>
                    <a href="{{ route('digitalocean-droplet.form') }}"
                       class="inline-block bg-green-600 text-white py-2 px-5 rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm sm:text-base">
                        Create Droplets
                    </a>
                </div>

                <!-- Documentation -->
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-blue-600 mb-3">Documentation</h2>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">
                        Learn how to deploy your Laravel apps and troubleshoot common issues.
                    </p>
                    <a href="{{ url('docs.index') }}"
                       class="inline-block bg-blue-600 text-white py-2 px-5 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm sm:text-base">
                        View Docs
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

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