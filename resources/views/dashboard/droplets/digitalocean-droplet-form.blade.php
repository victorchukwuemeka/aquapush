@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl mb-6 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                Create New Droplet
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Deploy your Laravel application in minutes. Need help? 
                <a href="{{ route('get-ssh') }}" class="text-red-600 hover:text-red-700 font-semibold underline decoration-2 underline-offset-2">
                    Learn how to get your SSH key
                </a>
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Progress Steps -->
            <div class="bg-gradient-to-r from-red-50 to-white px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between max-w-2xl mx-auto">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                        <span class="text-sm font-semibold text-gray-900 hidden sm:inline">Configuration</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 mx-4"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                        <span class="text-sm font-semibold text-gray-500 hidden sm:inline">Deployment</span>
                    </div>
                    <div class="flex-1 h-0.5 bg-gray-200 mx-4"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                        <span class="text-sm font-semibold text-gray-500 hidden sm:inline">Complete</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('create.droplet') }}" method="POST" class="p-8 sm:p-10 space-y-8">
                @csrf

                <!-- Credentials Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Credentials</h2>
                    </div>

                    <!-- API Token -->
                    <div class="group">
                        <label for="api_token" class="block text-sm font-semibold text-gray-700 mb-2">
                            DigitalOcean API Token
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="api_token"
                                name="api_token"
                                value="{{ old('api_token') }}"
                                placeholder="dop_v1_..."
                                class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('api_token') border-red-300 @enderror"
                                required
                            >
                            <button type="button" onclick="togglePassword('api_token')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('api_token')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- SSH Key -->
                    <div class="group">
                        <label for="ssh_key" class="block text-sm font-semibold text-gray-700 mb-2">
                            SSH Public Key
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="ssh_key"
                            name="ssh_key"
                            rows="4"
                            placeholder="ssh-rsa AAAAB3NzaC1yc2E..."
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 font-mono text-sm @error('ssh_key') border-red-300 @enderror"
                            required
                        >{{ old('ssh_key') }}</textarea>
                        <p class="text-sm text-gray-500 mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Need help? <a href="{{ route('get-ssh') }}" class="text-red-600 hover:text-red-700 font-medium underline">Generate SSH key</a>
                        </p>
                        @error('ssh_key')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Configuration Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Server Configuration</h2>
                    </div>

                    <!-- Droplet Name -->
                    <div class="group">
                        <label for="droplet_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Droplet Name
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="droplet_name"
                            name="droplet_name"
                            value="{{ old('droplet_name') }}"
                            placeholder="my-laravel-app"
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('droplet_name') border-red-300 @enderror"
                            required
                        >
                        @error('droplet_name')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Region & Size Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Region -->
                        <div class="group">
                            <label for="region" class="block text-sm font-semibold text-gray-700 mb-2">
                                Region
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select
                                    name="region"
                                    id="region"
                                    required
                                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 appearance-none bg-white @error('region') border-red-300 @enderror"
                                >
                                    <option value="">Select region...</option>
                                    @foreach($regions as $region => $description)
                                        <option value="{{ $region }}" {{ old('region') == $region ? 'selected' : '' }}>{{ $description }}</option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            @error('region')
                                <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Droplet Size -->
                        <div class="group">
                            <label for="droplet_size" class="block text-sm font-semibold text-gray-700 mb-2">
                                Size
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select
                                    name="droplet_size"
                                    id="droplet_size"
                                    required
                                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 appearance-none bg-white @error('droplet_size') border-red-300 @enderror"
                                >
                                    <option value="">Select size...</option>
                                    @foreach($dropletSizes as $size => $description)
                                        <option value="{{ $size }}" {{ old('droplet_size') == $size ? 'selected' : '' }}>{{ $description }}</option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            @error('droplet_size')
                                <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="group">
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                            Operating System
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select
                                name="image"
                                id="image"
                                required
                                class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 appearance-none bg-white @error('image') border-red-300 @enderror"
                            >
                                <option value="">Select OS...</option>
                                @foreach($images as $imageId => $imageDescription)
                                    <option value="{{ $imageId }}" {{ old('image') == $imageId ? 'selected' : '' }}>{{ $imageDescription }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        @error('image')
                            <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="pt-6 border-t border-gray-100">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-4 px-8 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold text-lg flex items-center justify-center gap-3 group"
                    >
                        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Create Droplet Now
                    </button>
                    <p class="text-center text-sm text-gray-500 mt-4">
                        Deployment usually takes 3-5 minutes
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    field.type = field.type === 'password' ? 'text' : 'password';
}
</script>

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