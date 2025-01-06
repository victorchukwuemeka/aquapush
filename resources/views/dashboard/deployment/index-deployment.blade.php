@extends('layouts.app')

@section('content')
<div class="flex flex-col lg:flex-row">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Deployment Dashboard</h1>

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

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Droplet Name</th>
                        <th class="border border-gray-300 px-4 py-2">Region</th>
                        <th class="border border-gray-300 px-4 py-2">IP Address</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">Show</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($droplets as $droplet)
                        <tr class="hover:bg-gray-100">
                            <td class="border border-gray-300 px-4 py-2">{{ $droplet->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $droplet->region }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $droplet->ip_address }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <span class="px-2 py-1 text-sm font-semibold rounded-full 
                                    {{ $droplet->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($droplet->status) }}
                                </span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="{{ route('droplet.show', $droplet->id) }}" class="text-blue-500 hover:underline">
                                    {{ __('Show Droplet') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-300 px-4 py-2 text-center">
                                No droplets found. 
                                <a href="{{ route('deploy.create') }}" class="text-blue-500 hover:underline">Deploy one now!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
@endsection