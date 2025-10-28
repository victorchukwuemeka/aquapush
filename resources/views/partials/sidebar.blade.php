<div class="w-64 bg-red-600 text-white flex flex-col">
    <div class="py-6 px-4 text-center border-b border-red-500">
        <h1 class="text-2xl font-bold">AquaPush</h1>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('dashboard') }}" class="block py-2 px-4 rounded hover:bg-red-500">
            Dashboard
        </a>
        <a href="{{ route('droplets.index') }}" class="block py-2 px-4 rounded hover:bg-red-500">
            Droplets
        </a>
        
        <a href="{{ route('account.settings.index') }}" class="block py-2 px-4 rounded hover:bg-red-500">
            Account Settings
        </a>
        <a href="{{ route('deploy.new') }}" class="block py-2 px-4 rounded hover:bg-red-500">
            Deploy New Application
        </a>
    </nav>
    <div class="px-4 py-6 border-t border-red-500">
        <a href="{{ url('logout') }}" class="block py-2 px-4 rounded bg-red-700 text-center hover:bg-red-800">
            Logout
        </a>
    </div>
</div>
