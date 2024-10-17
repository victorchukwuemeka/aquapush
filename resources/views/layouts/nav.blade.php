<header class="bg-white shadow">
    <div class="container mx-auto flex justify-between items-center px-4 py-4">
        <div class="flex items-center">
            <a href="/" class="text-2xl font-semibold text-red-600 hover:text-red-500 transition duration-300">
                AquaPush
            </a>
        </div>

        <nav class="hidden sm:flex space-x-6">
            <a href="{{ url('features') }}" class="text-gray-700 hover:text-red-600 transition duration-300">Features</a>
            <a href="{{ url('about') }}" class="text-gray-700 hover:text-red-600 transition duration-300">About</a>
            <a href="{{ url('contact') }}" class="text-gray-700 hover:text-red-600 transition duration-300">Contact</a>
        </nav>

        <div class="flex items-center">
            <a href="{{ route('auth.redirect') }}" class="text-gray-700 hover:text-red-600 transition duration-300 mr-4">
                {{ __('GitHub login') }}
            </a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-gray-700 hover:text-red-600 transition duration-300">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
