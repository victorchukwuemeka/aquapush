@extends("layouts.app")

@section("content")
<div class="container mx-auto text-center py-20">
    <h1 class="text-5xl font-extrabold mb-6 text-red-600">Welcome to AquaPush</h1>
    <p class="text-lg text-gray-700 mb-8">Effortlessly deploy your Laravel applications to DigitalOcean with ease and speed.</p>
    <a href="{{ url('upload') }}" class="bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out shadow-md">
        Get Started
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 my-12">
    <div class="p-6 border border-gray-200 rounded-lg shadow-lg text-center">
        <h2 class="font-bold text-xl mb-2 text-red-600">Easy Upload</h2>
        <p class="text-gray-600">Upload your Laravel site with just a few clicks and no hassle.</p>
    </div>
    <div class="p-6 border border-gray-200 rounded-lg shadow-lg text-center">
        <h2 class="font-bold text-xl mb-2 text-red-600">Fast Deployment</h2>
        <p class="text-gray-600">Deploy your applications to DigitalOcean in record time.</p>
    </div>
    <div class="p-6 border border-gray-200 rounded-lg shadow-lg text-center">
        <h2 class="font-bold text-xl mb-2 text-red-600">Secure Hosting</h2>
        <p class="text-gray-600">Keep your site running securely with our optimized solutions.</p>
    </div>
</div>
@endsection
