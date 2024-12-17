@extends("layouts.app")

@section("content")
<div class="container mx-auto text-center py-20">
    <h1 class="text-6xl font-extrabold mb-8 text-red-600">Welcome to AquaPush</h1>
    <p class="text-xl text-gray-700 mb-8">Effortlessly deploy your Laravel applications to DigitalOcean with speed and security.</p>
    
    <div class="flex justify-center gap-4">
        @if(Auth::check())
            <a href="{{ route('dashboard') }}" class="bg-red-600 text-white py-3 px-8 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out shadow-md">
                Go to Dashboard
            </a>
        @else
            <a href="{{ route('auth.redirect') }}" class="bg-red-600 text-white py-3 px-8 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out shadow-md">
                Sign in with GitHub
            </a>
        @endif
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-8 my-16">
    <div class="p-6 border border-gray-200 rounded-lg shadow-lg text-center">
        <h2 class="font-bold text-2xl mb-3 text-red-600">Easy Upload</h2>
        <p class="text-gray-600">Upload your Laravel site with just a few clicks and no hassle.</p>
    </div>
    <div class="p-6 border border-gray-200 rounded-lg shadow-lg text-center">
        <h2 class="font-bold text-2xl mb-3 text-red-600">Fast Deployment</h2>
        <p class="text-gray-600">Deploy your applications to DigitalOcean in record time.</p>
    </div>
    <div class="p-6 border border-gray-200 rounded-lg shadow-lg text-center">
        <h2 class="font-bold text-2xl mb-3 text-red-600">Secure Hosting</h2>
        <p class="text-gray-600">Keep your site running securely with our optimized solutions.</p>
    </div>
</div>

<div class="text-center mt-12">
    <h3 class="text-xl text-gray-700">Start your journey with AquaPush today!</h3>
    <p class="text-gray-500 mt-2">Sign in or create an account to get started.</p>
</div>
@endsection
