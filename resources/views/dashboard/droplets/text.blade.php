@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Droplet Details: {{ $droplet->name }}</h1>

        <!-- Back Button -->
        <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline mb-6 inline-block">&larr; Back to Dashboard</a>

        <!-- Droplet Details -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach(['Name' => 'name', 'Region' => 'region', 'IP Address' => 'ip_address', 'Status' => 'status', 'Created At' => 'created_at', 'Updated At' => 'updated_at'] as $label => $field)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                        <p class="mt-1 text-lg text-gray-900">
                            @if($field === 'status')
                                <span class="px-2 py-1 text-sm font-semibold rounded-full {{ $droplet->$field == 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($droplet->$field) }}
                                </span>
                            @elseif(in_array($field, ['created_at', 'updated_at']))
                                {{ $droplet->$field->format('M d, Y H:i:s') }}
                            @else
                                {{ $droplet->$field }}
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Setup Project Section -->
        <div class="mb-6">
            <button id="toggleSetupForm" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out shadow-md">
                Set Up Project
            </button>
        </div>

        <div id="setupForm" class="bg-white shadow-md rounded-lg p-6 hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Setup Project</h2>
            @foreach(['error' => 'red', 'success' => 'green'] as $type => $color)
                @if(session($type))
                    <div class="mb-6 bg-{{ $color }}-100 border border-{{ $color }}-400 text-{{ $color }}-700 p-4 rounded">
                        {{ session($type) }}
                    </div>
                @endif
            @endforeach

            <form action="{{ url('droplet.setup', $droplet->id) }}" method="POST">
                @csrf

                @foreach([
                    'github_repo' => 'GitHub Repository',
                    'db_name' => 'Database Name',
                    'db_user' => 'Database User',
                    'db_password' => 'Database Password'
                ] as $name => $label)
                    <div class="mb-6">
                        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                        <input type="{{ $name === 'db_password' ? 'password' : 'text' }}" id="{{ $name }}" name="{{ $name }}" placeholder="Enter {{ strtolower($label) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                @endforeach

                <div class="mb-6">
                    <label for="php_version" class="block text-sm font-medium text-gray-700">PHP Version</label>
                    <select id="php_version" name="php_version" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        @foreach(['8.2', '8.1', '8.0', '7.4'] as $version)
                            <option value="{{ $version }}">PHP {{ $version }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out shadow-md">Setup Project</button>
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

<script>
    document.getElementById('toggleSetupForm').addEventListener('click', function() {
        document.getElementById('setupForm').classList.toggle('hidden');
    });
</script>
@endsection