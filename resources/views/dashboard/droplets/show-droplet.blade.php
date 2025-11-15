{{-- resources/views/dashboard/droplets/show-droplet.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('droplets.index') }}" class="inline-flex items-center text-gray-600 hover:text-red-600 transition mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Droplets
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Droplet Details</h1>
                    <p class="text-gray-600 mt-1">Manage your droplet and deploy applications</p>
                </div>
            </div>
        </div>

        {{-- Error Message --}}
        @if(isset($error))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-red-800">Error</h3>
                        <p class="text-red-700">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        @isset($dropletData)
            <!-- Status Banner -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mr-4 {{ $dropletData['droplet']['status'] === 'active' ? 'bg-green-100' : 'bg-red-100' }}">
                            @if($dropletData['droplet']['status'] === 'active')
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $dropletData['droplet']['name'] }}</h2>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $dropletData['droplet']['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <span class="w-2 h-2 rounded-full mr-2 {{ $dropletData['droplet']['status'] === 'active' ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></span>
                                    {{ ucfirst($dropletData['droplet']['status']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('laravel_project.configure', ['droplet_id' => $dropletData['droplet']['id']]) }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Deploy New Project
                        </a>
                        <form action="{{ route('droplets.delete', ['droplet_id' => $dropletData['droplet']['id']]) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this droplet? This action cannot be undone.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-white text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200 border-2 border-gray-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Droplet
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- General Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">General Information</h3>
                    </div>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Droplet ID</dt>
                            <dd class="text-sm text-gray-900 font-mono">{{ $dropletData['droplet']['id'] }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Name</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $dropletData['droplet']['name'] }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Region</dt>
                            <dd class="text-sm text-gray-900">{{ $dropletData['droplet']['region']['name'] ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <dt class="text-sm font-medium text-gray-600">Created</dt>
                            <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($dropletData['droplet']['created_at'])->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Resource Details Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Resources</h3>
                    </div>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Memory</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $dropletData['droplet']['memory'] }} MB</dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">VCPUs</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $dropletData['droplet']['vcpus'] }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <dt class="text-sm font-medium text-gray-600">Disk</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $dropletData['droplet']['disk'] }} GB</dd>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <dt class="text-sm font-medium text-gray-600">Transfer</dt>
                            <dd class="text-sm text-gray-900 font-semibold">{{ $dropletData['droplet']['size']['transfer'] ?? 'N/A' }} TB</dd>
                        </div>
                    </dl>
                </div>

                <!-- Network Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Network</h3>
                    </div>
                    <dl class="space-y-3">
                        @if(isset($dropletData['droplet']['networks']['v4'][0]))
                            <div class="py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-600 mb-1">IPv4 Address</dt>
                                <dd class="flex items-center justify-between">
                                    <span class="text-sm text-gray-900 font-mono">{{ $dropletData['droplet']['networks']['v4'][0]['ip_address'] }}</span>
                                    <button onclick="copyToClipboard('{{ $dropletData['droplet']['networks']['v4'][0]['ip_address'] }}')" 
                                            class="text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </dd>
                            </div>
                        @endif
                        @if(isset($dropletData['droplet']['networks']['v6'][0]))
                            <div class="py-2 border-b border-gray-100">
                                <dt class="text-sm font-medium text-gray-600 mb-1">IPv6 Address</dt>
                                <dd class="text-sm text-gray-900 font-mono truncate">{{ $dropletData['droplet']['networks']['v6'][0]['ip_address'] }}</dd>
                            </div>
                        @endif
                        <div class="py-2">
                            <dt class="text-sm font-medium text-gray-600 mb-1">Firewall</dt>
                            <dd class="text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Deployed Apps Section (Placeholder) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Deployed Applications</h3>
                    </div>
                    <span class="text-sm text-gray-500">0 apps</span>
                </div>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No applications deployed yet</h4>
                    <p class="text-gray-600 mb-6">Deploy your first Laravel application to get started</p>
                    <a href="{{ route('laravel_project.configure', ['droplet_id' => $dropletData['droplet']['id']]) }}" 
                       class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Deploy Your First App
                    </a>
                </div>
            </div>

        @endisset
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
        toast.textContent = 'IP address copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection