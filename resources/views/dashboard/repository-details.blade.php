@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Repository Details</h1>

        @if(isset($repoData) && !empty($repoData))
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-2">{{ $repoData['name'] }}</h2>
                <p class="text-gray-700 mb-4">{{ $repoData['description'] ?? 'No description available.' }}</p>

                <div class="flex items-center mb-4">
                    <span class="text-sm font-medium text-gray-600">Owner:</span>
                    <a href="{{ $repoData['owner']['html_url'] }}" class="ml-2 text-blue-500 hover:underline">{{ $repoData['owner']['login'] }}</a>
                </div>

                <div class="flex items-center mb-4">
                    <span class="text-sm font-medium text-gray-600">Stars:</span>
                    <span class="ml-2">{{ $repoData['stargazers_count'] }}</span>
                </div>

                <div class="flex items-center mb-4">
                    <span class="text-sm font-medium text-gray-600">Forks:</span>
                    <span class="ml-2">{{ $repoData['forks_count'] }}</span>
                </div>

                <div class="flex items-center mb-4">
                    <span class="text-sm font-medium text-gray-600">Open Issues:</span>
                    <span class="ml-2">{{ $repoData['open_issues_count'] }}</span>
                </div>

                <div class="flex items-center mb-4">
                    <span class="text-sm font-medium text-gray-600">Language:</span>
                    <span class="ml-2">{{ $repoData['language'] ?? 'Not specified' }}</span>
                </div>

                <div class="mt-4">
                    <a href="{{ $repoData['html_url'] }}" target="_blank" 
                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">View on GitHub</a>
                </div>
            </div>
        @else
            <p class="text-red-500">No repository data found.</p>
        @endif

        <div>
            <a href="{{ route('digitalocean.config')}}"> 
                digitalocean
            </a>
        </div>

        <div class="mt-6">
            <a href="{{ url()->previous() }}" class="text-blue-500 hover:underline">Back</a>
        </div>
    </div>

@endsection
