@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Configure Deployment</h2>

        <!-- Droplet Details -->
        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <h3 class="text-xl font-bold text-gray-700">Droplet Information</h3>
            <p><strong>Name:</strong> {{ $droplet->name }}</p>
            <p><strong>IP Address:</strong> {{ $droplet->ip_address }}</p>
            <p><strong>Status:</strong>
                <span class="px-2 py-1 text-white text-sm rounded 
                    {{ $droplet->status == 'active' ? 'bg-green-600' : 'bg-red-600' }}">
                    {{ ucfirst($droplet->status) }}
                </span>
            </p>
        </div>

        <!-- Deployment Configuration Form -->
        <form action="{{ route('droplet.setup', [$droplet->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="droplet_id" value="{{ $droplet->droplet_id }}">
        
            <!-- Repository URL -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Repository URL (GitHub/GitLab/Bitbucket)</label>
                <input type="text" name="repository_url" class="w-full p-2 border rounded-lg" 
                       placeholder="https://github.com/username/repository.git" required>
            </div>
        
            <!-- Branch Selection -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Branch Name</label>
                <input type="text" name="branch" class="w-full p-2 border rounded-lg" placeholder="main" required>
            </div>
        
            <!-- PHP Version -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Select PHP Version</label>
                <select name="php_version" required class="w-full p-3 border rounded-lg">
                    @foreach($phpVersions as $version)
                        <option value="{{ $version }}">{{ $version }}</option>
                    @endforeach
                </select>
            </div>
        
            <!-- Web Server -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Select Web Server</label>
                <select name="web_server" required class="w-full p-3 border rounded-lg">
                    @foreach($webServers as $server)
                        <option value="{{ $server }}">{{ ucfirst($server) }}</option>
                    @endforeach
                </select>
            </div>
        
            <!-- Database Setup -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Select Database</label>
                <select name="database" class="w-full p-2 border rounded-lg">
                    <option value="mysql">MySQL</option>
                    <option value="postgres">PostgreSQL</option>
                    <option value="sqlite">SQLite</option>
                    <option value="none">No Database</option>
                </select>
            </div>
        
            <!-- SSH Key -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">SSH Key (Optional)</label>
                <input type="text" name="ssh_key" class="w-full p-2 border rounded-lg" 
                       placeholder="Paste your SSH key if needed">
            </div>
        
            <!-- Domain Name -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Domain Name (Optional)</label>
                <input type="text" name="domain_name" class="w-full p-2 border rounded-lg" 
                       placeholder="example.com">
            </div>
        
            <!-- Deployment Method -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Deployment Method</label>
                <select name="deployment_method" required class="w-full p-3 border rounded-lg">
                    <option value="manual">Manual</option>
                    <option value="automated">Automated (GitHub Actions, Laravel Forge)</option>
                </select>
            </div>
        
            <!-- Composer Install -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">
                    <input type="checkbox" name="composer_install" value="1">
                    Run <code>composer install</code> after deployment
                </label>
            </div>
        
            <!-- NPM Install -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">
                    <input type="checkbox" name="npm_install" value="1">
                    Run <code>npm install && npm run build</code>
                </label>
            </div>
        
            <!-- Queue Setup -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">
                    <input type="checkbox" name="queue_worker" value="1">
                    Run <code>php artisan queue:work</code>
                </label>
            </div>
        
            <!-- Storage Setup -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">
                    <input type="checkbox" name="storage_link" value="1">
                    Run <code>php artisan storage:link</code>
                </label>
            </div>
        
            <!-- Environment Variables -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Environment Variables (Optional)</label>
                <textarea name="env_variables" rows="4" class="w-full p-2 border rounded-lg" 
                          placeholder="APP_NAME=Laravel&#10;APP_ENV=production"></textarea>
            </div>
        
            <!-- Submit Button -->
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-md">
                Deploy Project
            </button>
        </form>
        
      
    </div>
</div>
@endsection
