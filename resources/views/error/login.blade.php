@extends('layouts.app')

@section('content')
<div class="container mx-auto text-center py-20">
    <h1 class="text-4xl font-bold mb-4">Login Error</h1>
    <p class="text-lg mb-8">Oops! It seems there was an error with your login attempt.</p>
    <p class="mb-4">Please check your credentials and try again.</p>
    <a href="{{ url('login') }}" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">Back to Login</a>
</div>
@endsection
