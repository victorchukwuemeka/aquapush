@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        
        <div class="flex flex-col items-center text-center">
            <div class="bg-red-100 text-red-600 w-16 h-16 flex items-center justify-center rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M12 19a7 7 0 100-14 7 7 0 000 14z" />
                </svg>
            </div>

            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Something went wrong</h1>
            <p class="text-gray-500 mb-6">
                {{ $message ?? 'An unexpected error occurred.' }}
            </p>

            <div class="bg-gray-100 rounded-lg p-4 text-left w-full text-sm text-gray-600 mb-6">
                <p><strong>Status Code:</strong> {{ $status ?? 'N/A' }}</p>
                @if(!empty($details))
                    <p class="mt-2"><strong>Details:</strong> {{ $details }}</p>
                @endif
            </div>

            <a href="{{ url('/digitalocean/droplets') }}"
               class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                Return to Droplets
            </a>
        </div>
    </div>

    <p class="mt-8 text-sm text-gray-400">
        &copy; {{ date('Y') }} aquaPush. All rights reserved.
    </p>
</div>
@endsection
