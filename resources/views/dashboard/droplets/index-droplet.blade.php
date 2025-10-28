@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="flex flex-col lg:flex-row">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-10">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-10">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Deployment Dashboard
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Manage your live droplets and deployed projects
                    </p>
                </div>

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between">
                        <span>
                            {{ session('success') }}
                        </span>
                        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                            Cross
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between">
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                            Cross
                        </button>
                    </div>
                @endif

                <!-- Droplets Grid -->
                @forelse ($droplets as $droplet)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 hover:shadow-md transition-shadow duration-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Left: Info -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full {{ $droplet->status == 'active' ? 'bg-green-500' : 'bg-yellow-500' }} animate-pulse"></div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $droplet->name }}</h3>
                                </div>

                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>{{ $droplet->region }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span class="font-mono">{{ $droplet->ip_address }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Created {{ $droplet->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Actions -->
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    {{ $droplet->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($droplet->status) }}
                                </span>
                                <a href="{{ route('droplet.show', $droplet->droplet_id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Droplets Yet</h3>
                            <p class="text-gray-600 mb-8">Get started by deploying your first Laravel app in seconds.</p>
                            <a href="{{ route('deploy.new') }}"
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-300 to-red-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Creat Your Firt Droplet 
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>
@endsection