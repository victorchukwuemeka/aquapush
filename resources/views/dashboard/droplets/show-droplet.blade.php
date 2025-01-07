@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Droplet Details: {{ $droplet->name }}</h1>

        <!-- Back Button -->
        <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline mb-6 inline-block">
            &larr; Back to Dashboard
        </a>

        <!-- Droplet Details -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $droplet->name }}</p>
                </div>

                <!-- Region -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Region</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $droplet->region }}</p>
                </div>

                <!-- IP Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">IP Address</label>
                    <p class="mt-1 text-lg text-gray-900">{{ $droplet->ip_address }}</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <p class="mt-1 text-lg text-gray-900">
                        <span class="px-2 py-1 text-sm font-semibold rounded-full 
                            {{ $droplet->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($droplet->status) }}
                        </span>
                    </p>
                </div>

                <!-- Created At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Created At</label>
                    <p class="mt-1 text-lg text-gray-900">
                        {{ $droplet->created_at->format('M d, Y H:i:s') }}
                    </p>
                </div>

                <!-- Updated At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Updated At</label>
                    <p class="mt-1 text-lg text-gray-900">
                        {{ $droplet->updated_at->format('M d, Y H:i:s') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Toggle Button for Setup Form -->
        <div class="mb-6">
            <button
                id="toggleSetupForm"
                class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out shadow-md"
            >
                Set Up Project
            </button>
        </div>

        <!-- Setup Project Section (Hidden by Default) -->
        <div id="setupForm" class="bg-white shadow-md rounded-lg p-6 hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Setup Project</h2>

            <!-- Error Alert -->
            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 p-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Success Alert -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 p-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Setup Form -->
            <form action="{{ url('droplet.setup', $droplet->id) }}" method="POST">
                @csrf

                <!-- GitHub Repository -->
                <div class="mb-6">
                    <label for="github_repo" class="block text-sm font-medium text-gray-700">GitHub Repository</label>
                    <input
                        type="text"
                        id="github_repo"
                        name="github_repo"
                        placeholder="e.g., https://github.com/username/repo.git"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>

                <!-- PHP Version -->
                <div class="mb-6">
                    <label for="php_version" class="block text-sm font-medium text-gray-700">PHP Version</label>
                    <select
                        id="php_version"
                        name="php_version"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                        <option value="8.2">PHP 8.2</option>
                        <option value="8.1">PHP 8.1</option>
                        <option value="8.0">PHP 8.0</option>
                        <option value="7.4">PHP 7.4</option>
                    </select>
                </div>

                <!-- Database Credentials -->
                <div class="mb-6">
                    <label for="db_name" class="block text-sm font-medium text-gray-700">Database Name</label>
                    <input
                        type="text"
                        id="db_name"
                        name="db_name"
                        placeholder="e.g., laravel_db"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="db_user" class="block text-sm font-medium text-gray-700">Database User</label>
                    <input
                        type="text"
                        id="db_user"
                        name="db_user"
                        placeholder="e.g., laravel_user"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="db_password" class="block text-sm font-medium text-gray-700">Database Password</label>
                    <input
                        type="password"
                        id="db_password"
                        name="db_password"
                        placeholder="Enter a strong password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out shadow-md"
                    >
                        Setup Project
                    </button>
                </div>
            </form>
        </div>

        <!-- Setup Output -->
        <div class="mt-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Setup Output</h3>
            <pre class="bg-gray-100 p-4 rounded-lg text-gray-700 overflow-auto">{{ session('output') ?? 'No output yet.' }}</pre>
        </div>
    </main>
</div>

<!-- JavaScript to Toggle Form Visibility -->
<script>
    document.getElementById('toggleSetupForm').addEventListener('click', function() {
        const setupForm = document.getElementById('setupForm');
        setupForm.classList.toggle('hidden');
    });
</script>
@endsection