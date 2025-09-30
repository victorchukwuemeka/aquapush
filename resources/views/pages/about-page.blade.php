@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 max-w-5xl">
    <!-- Header Section -->
    <div class="text-center mb-12 animate-fade-in">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-red-600 mb-4 tracking-tight">
            About AquaPush
        </h1>
        <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
            AquaPush simplifies Laravel app deployment to DigitalOcean,
             empowering developers to launch projects faster and smarter.
        </p>
    </div>

    <!-- Mission Section -->
    <section class="mb-12">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden animate-slide-up">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Our Mission</h2>
            </div>
            <div class="p-6 sm:p-8">
                <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
                    At AquaPush, we believe deploying Laravel applications should be effortless.
                     Our mission is to provide developers with a seamless, secure,
                      and intuitive platform to deploy and manage their apps on DigitalOcean, 
                      saving time and reducing complexity.
                </p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="mb-12">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden animate-slide-up">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Why Choose AquaPush?</h2>
            </div>
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Lightning-Fast Deployment</h3>
                        <p class="text-gray-600 text-sm">Deploy your Laravel apps to DigitalOcean in minutes
                             with our streamlined process.</p>
                    </div>
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-2.76 2.24-5 5-5s5 2.24 5 5-2.24 5-5 5-5-2.24-5-5zM2 12h8m4 0h8" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Secure Integration</h3>
                        <p class="text-gray-600 text-sm">Manage DigitalOcean API tokens and 
                            SSH keys securely for hassle-free setup.</p>
                    </div>
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m9-3c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Real-Time Monitoring</h3>
                        <p class="text-gray-600 text-sm">Track your deployments in real-time with clear, 
                            actionable updates.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="mb-12">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden animate-slide-up">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">How AquaPush Works</h2>
            </div>
            <div class="p-6 sm:p-8">
                <ol class="list-decimal pl-6 space-y-4 text-gray-700">
                    <li class="text-base sm:text-lg">Generate an SSH key following our <a href="{{ url('get-ssh') }}" class="text-red-600 hover:underline">simple guide</a>.</li>
                    <li class="text-base sm:text-lg">
                        Configure your DigitalOcean API token and droplet settings in the
                        <a href="{{ url('deploy.create') }}" class="text-red-600 hover:underline">
                          deployment form
                        </a>.
                    </li>
                    <li class="text-base sm:text-lg">
                        Deploy your Laravel app and monitor progress from your 
                        <a href="{{ url('dashboard') }}" class="text-red-600 hover:underline">
                            dashboard
                        </a>.
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="text-center animate-fade-in">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-4">Ready to Deploy?</h2>
        <p class="text-gray-600 text-base sm:text-lg mb-6 max-w-xl mx-auto">
            Join developers worldwide who trust AquaPush to deploy their Laravel apps effortlessly.
        </p>
        <a href="{{ url('deploy.create') }}"
           class="inline-block bg-red-600 text-white py-3 px-8 rounded-lg hover:bg-red-700 transition-colors duration-300 shadow-md text-base sm:text-lg font-medium">
            Start Deploying Now
        </a>
    </section>
</div>

<!-- Custom CSS for Animations -->
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    .animate-slide-up {
        animation: slideUp 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection