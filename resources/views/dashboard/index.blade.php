@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="flex-1 bg-gray-100">
        <div class="container mx-auto py-10 px-6">
            <!-- Welcome Section -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-red-600 mb-4">Welcome to Your AquaPush Dashboard</h1>
                <p class="text-gray-700">Manage your deployments, track your droplets, and get the most out of AquaPush.</p>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg text-center">
                    <h2 class="text-2xl font-semibold text-red-600">Deployments</h2>
                    <p class="text-gray-600 mt-2">Track and manage your site deployments efficiently.</p>
                    <a href="{{ route('deployments.index') }}" class="inline-block mt-4 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
                        View Deployments  kkk
                    </a>
                </div>
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg text-center">
                    <h2 class="text-2xl font-semibold text-red-600">API Tokens</h2>
                    <p class="text-gray-600 mt-2">Manage your DigitalOcean API tokens for easy integration.</p>
                    <a href="{{ route('api.tokens.index') }}" class="inline-block mt-4 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
                        Manage Tokens
                    </a>
                </div>
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg text-center">
                    <h2 class="text-2xl font-semibold text-red-600">Account Settings</h2>
                    <p class="text-gray-600 mt-2">Update your profile and manage account preferences.</p>
                    <a href="{{ route('account.settings.index') }}" class="inline-block mt-4 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
