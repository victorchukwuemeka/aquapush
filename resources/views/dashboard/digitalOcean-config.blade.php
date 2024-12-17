@extends("layouts.app")

@section('content')

    <div class="container mx-auto p-6">


        
        <h1 class="text-3xl font-bold mb-6">DigitalOcean Configuration</h1>

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('digitalocean.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
            @csrf

            <div class="mb-4">
                <label for="api_token" class="block text-gray-700 text-sm font-bold mb-2">DigitalOcean API Token:</label>
                <input type="text" name="api_token" id="api_token" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter your DigitalOcean API Token">
            </div>

            <div class="mb-4">
                <label for="droplet_size" class="block text-gray-700 text-sm font-bold mb-2">Select Droplet Size:</label>
                <select name="droplet_size" id="droplet_size" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">-- Select Droplet Size --</option>
                    @foreach($dropletSizes as $size => $description)
                        <option value="{{ $size }}">{{ $description }}</option>
                    @endforeach
                </select>
            </div>
                

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save Configuration
                </button>
            </div>
        </form>
    </div>
@endsection