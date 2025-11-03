{{-- resources/views/dashboard/droplets/show-droplet.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex flex-col lg:flex-row space- y-6 lg:space-y-0 lg:space-x-8">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4 bg-red-600 text-white p-6 rounded-xl shadow-xl">
                @include('partials.sidebar')
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 bg-white p-6 rounded-lg shadow-lg">
                {{-- Error Message --}}
                @if(isset($error))
                    <div class="bg-red-500 text-white p-4 rounded-md shadow-md mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <strong>Error:</strong> {{ $error }}
                    </div>
                @endif

                {{-- Droplet Details --}}
                @isset($dropletData)
                    <div class="droplet-details">
                        <h2 class="text-3xl font-semibold text-gray-800 mb-6 flex items-center">
                            <svg class="w-8 h-8 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                            </svg>
                            Droplet Details
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Droplet Information -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-xl font-semibold text-gray-700 mb-4">General Information</h3>
                                <ul class="space-y-3">
                                    <li class="flex justify-between text-gray-700">
                                        <span class="font-medium">Droplet ID:</span>
                                        <span>{{ $dropletData['droplet']['id'] }}</span>
                                        
                                    </li>
                                    <li class="flex justify-between text-gray-700">
                                        <span class="font-medium">Name:</span>
                                        <span>{{ $dropletData['droplet']['name'] }}</span>
                                    </li>
                                    <li class="flex justify-between text-gray-700">
                                        <span class="font-medium">Status:</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $dropletData['droplet']['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $dropletData['droplet']['status'] }}
                                        </span>
                                    </li>
                                    <li class="flex justify-between text-gray-700">
                                        <span class="font-medium">Created At:</span>
                                        <span>{{ \Carbon\Carbon::parse($dropletData['droplet']['created_at'])->format('M d, Y H:i') }}</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Resource Information -->
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-xl font-semibold text-gray-700 mb-4">Resource Details</h3>
                                <ul class="space-y-3">
                                    <li class="flex justify-between text-gray-700">
                                        <span class="font-medium">Memory:</span>
                                        <span>{{ $dropletData['droplet']['memory'] }} MB</span>
                                    </li>
                                    <li class="flex justify-between text-gray-700">
                                        <span class="font-medium">VCPUs:</span>
                                        <span>{{ $dropletData['droplet']['vcpus'] }}</span>
                                    </li>
                                    <li class="flex justify-between text-gray-700">
                                        <span class="font-medium">Disk:</span>
                                        <span>{{ $dropletData['droplet']['disk'] }} GB</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                     <!-- New Deployment Section -->
                     <div class="mt-6 border-t pt-4">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Project Deployment</h3>
                        <div class="flex space-x-4">

                            <a href="{{ route('laravel_project.configure', ['droplet_id' => $dropletData['droplet']['id']]) }}" 
                               class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                Deploy New Project
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('droplets.delete', ['droplet_id' => $dropletData['droplet']['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this droplet? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition">
                                    Delete Droplet
                                </button>
                            </form>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>
@endsection
