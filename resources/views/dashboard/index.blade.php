@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Main Content -->
    <main class="p-4 sm:p-6 lg:p-8">
        <div class="container mx-auto max-w-7xl">
            <!-- Welcome Section -->
            <div class="mb-12 animate-fade-in">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-2">
                            Welcome Back! 👋
                        </h1>
                        <p class="text-gray-600 text-lg">
                            Manage your droplets and deployments with ease
                        </p>
                    </div>
                    <a href="{{ route('digitalocean-droplet.form') }}"
                       class="inline-flex items-center gap-2 bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Droplet
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Servers</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalServers ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Active Deployments</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $activeDeployments ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Last Activity</p>
                            <p class="text-xl font-bold text-gray-900">{{ $lastActivity ?? 'Never' }}</p>
                        </div>
                        <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h2>
            </div>

            <!-- Action Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- My Droplets -->
                <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-red-200 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">My Droplets</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        View and manage all your servers, droplets, and deployment history in one place.
                    </p>
                    <a href="{{ route('droplets.index') }}"
                       class="inline-flex items-center gap-2 text-red-600 font-semibold hover:gap-3 transition-all duration-200">
                        View Droplets
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

                <!-- Create Droplet -->
                <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-green-200 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Create Droplet</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Provision a new DigitalOcean droplet configured and ready for Laravel deployment.
                    </p>
                    <a href="{{ route('digitalocean-droplet.form') }}"
                       class="inline-flex items-center gap-2 text-green-600 font-semibold hover:gap-3 transition-all duration-200">
                        Get Started
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

                <!-- Documentation -->
                <div class="group bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Documentation</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Learn how to deploy your Laravel apps and troubleshoot common deployment issues.
                    </p>
                    <a href="{{ url('docs.index') }}"
                       class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:gap-3 transition-all duration-200">
                        View Docs
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Custom CSS for Animations -->
<style>
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    @keyframes fadeIn {
        0% { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        100% { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
</style>
@endsection