@extends('layouts.app')

@section('content')

<div class="flex flex-col items-center justify-center h-screen bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 text-center">
        <div class="text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l2 2m-2-2l-2 2m2-2V8m0 4v4m0-4h4m-4-4H6m8 0h2m-2 0a2 2 0 110 4m0-4a2 2 0 100-4m-6 0a2 2 0 100 4m0-4a2 2 0 110 4" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mt-4">Oops! Error Detected</h1>
        <p class="text-gray-600 mt-2">It seems like your repository is not a Laravel project.</p>
        <p class="text-gray-500 mt-2">Please verify and try again.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block bg-red-600 text-white font-semibold py-2 px-4 rounded hover:bg-red-700">
            Go Back to Home
        </a>
    </div>
</div>

@endsection
