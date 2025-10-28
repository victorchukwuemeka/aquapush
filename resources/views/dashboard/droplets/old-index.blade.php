{{-- resources/views/droplets/index.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50">
    @include('partials.sidebar')

    <main class="flex-1 p-6 lg:p-10">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Deployment Dashboard</h1>

            <!-- LOOP THROUGH DROPLETS -->
            @forelse ($droplets as $droplet)
                {{-- THIS LINE INCLUDES THE CARD --}}
                @include('droplets._droplet-card', ['droplet' => $droplet])
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-600">No droplets found.</p>
                    <a href="{{ route('droplet.create') }}" class="text-indigo-600 hover:underline">Create one now</a>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection