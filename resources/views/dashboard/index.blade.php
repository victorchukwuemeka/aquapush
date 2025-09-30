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
                    Welcome to Your AquaPush Dashboard
                </h1>
                <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
                    Seamlessly manage your deployments, track droplets, and optimize your AquaPush experience.
                </p>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 text-center">
                    <h2 class="text-xl sm:text-2xl font-semibold text-red-600 mb-3">Deployments</h2>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">
                        Monitor and manage your site deployments with ease and efficiency.
                    </p>
                    <a href="{{ route('deployments.index') }}"
                       class="inline-block bg-red-600 text-white py-2 px-5 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm sm:text-base">
                        View Deployments
                    </a>
                </div>
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 text-center">
                    <h2 class="text-xl sm:text-2xl font-semibold text-red-600 mb-3">API Tokens</h2>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">
                        Securely manage your DigitalOcean API tokens for seamless integration.
                    </p>
                    <a href="{{ route('api.tokens.index') }}"
                       class="inline-block bg-red-600 text-white py-2 px-5 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm sm:text-base">
                        Manage Tokens
                    </a>
                </div>
                <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 text-center">
                    <h2 class="text-xl sm:text-2xl font-semibold text-red-600 mb-3">Account Settings</h2>
                    <p class="text-gray-600 text-sm sm:text-base mb-4">
                        Personalize your profile and configure account preferences.
                    </p>
                    <a href="{{ route('account.settings.index') }}"
                       class="inline-block bg-red-600 text-white py-2 px-5 rounded-lg hover:bg-red-700 transition-colors duration-200 text-sm sm:text-base">
                        Edit Profile
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