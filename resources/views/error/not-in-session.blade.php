@extends('layouts.app')

@section('content')

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 max-w-md text-center">
        <img src="{{ asset('images/error.svg') }}" alt="Error" class="w-32 mx-auto mb-6">
        <h1 class="text-2xl font-bold text-red-600 mb-4">Access Denied</h1>
        <p class="text-gray-700 mb-6">
            You can't deploy without logging in. Please log in with your GitHub account to continue.
        </p>
        <a href="{{ route('auth.redirect') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
            Log in with GitHub
        </a>
    </div>
</div>

@endsection
