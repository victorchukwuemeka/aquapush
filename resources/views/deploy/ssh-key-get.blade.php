@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Generate SSH Key on Your Local Machine</h1>

    <!-- Instructions for generating SSH key -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="bg-gray-100 px-6 py-4 rounded-t-lg">
            <h2 class="text-xl font-semibold">How to Generate an SSH Key</h2>
        </div>
        <div class="p-6">
            <ul class="flex border-b mb-4">
                <li class="mr-2">
                    <a href="#linux" class="tab-link inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold active" data-tab="linux">Linux</a>
                </li>
                <li class="mr-2">
                    <a href="#mac" class="tab-link inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" data-tab="mac">macOS</a>
                </li>
                <li class="mr-2">
                    <a href="#windows" class="tab-link inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" data-tab="windows">Windows</a>
                </li>
            </ul>

            <!-- Linux Instructions -->
            <div id="linux" class="tab-content active">
                <ol class="list-decimal pl-6">
                    <li class="mb-2">Open a terminal.</li>
                    <li class="mb-2">Run the following command to generate a new SSH key:
                        <pre class="bg-gray-100 p-4 rounded-md mt-2"><code>ssh-keygen -t rsa -b 4096 -C "your_email@example.com"</code></pre>
                    </li>
                    <li class="mb-2">When prompted, press <kbd class="bg-gray-200 px-2 py-1 rounded-md">Enter</kbd> to save the key to the default location (<code>~/.ssh/id_rsa</code>).</li>
                    <li class="mb-2">Set a passphrase (optional but recommended for added security).</li>
                    <li class="mb-2">Once the key is generated, display the public key:
                        <pre class="bg-gray-100 p-4 rounded-md mt-2"><code>cat ~/.ssh/id_rsa.pub</code></pre>
                    </li>
                    <li class="mb-2">Copy the public key and upload it below.</li>
                </ol>
            </div>

            <!-- macOS Instructions -->
            <div id="mac" class="tab-content hidden">
                <ol class="list-decimal pl-6">
                    <li class="mb-2">Open a terminal.</li>
                    <li class="mb-2">Run the following command to generate a new SSH key:
                        <pre class="bg-gray-100 p-4 rounded-md mt-2"><code>ssh-keygen -t rsa -b 4096 -C "your_email@example.com"</code></pre>
                    </li>
                    <li class="mb-2">When prompted, press <kbd class="bg-gray-200 px-2 py-1 rounded-md">Enter</kbd> to save the key to the default location (<code>~/.ssh/id_rsa</code>).</li>
                    <li class="mb-2">Set a passphrase (optional but recommended for added security).</li>
                    <li class="mb-2">Once the key is generated, display the public key:
                        <pre class="bg-gray-100 p-4 rounded-md mt-2"><code>cat ~/.ssh/id_rsa.pub</code></pre>
                    </li>
                    <li class="mb-2">Copy the public key and upload it below.</li>
                </ol>
            </div>

            <!-- Windows Instructions -->
            <div id="windows" class="tab-content hidden">
                <ol class="list-decimal pl-6">
                    <li class="mb-2">Open Git Bash (if installed) or Windows Subsystem for Linux (WSL).</li>
                    <li class="mb-2">Run the following command to generate a new SSH key:
                        <pre class="bg-gray-100 p-4 rounded-md mt-2"><code>ssh-keygen -t rsa -b 4096 -C "your_email@example.com"</code></pre>
                    </li>
                    <li class="mb-2">When prompted, press <kbd class="bg-gray-200 px-2 py-1 rounded-md">Enter</kbd> to save the key to the default location (<code>~/.ssh/id_rsa</code>).</li>
                    <li class="mb-2">Set a passphrase (optional but recommended for added security).</li>
                    <li class="mb-2">Once the key is generated, display the public key:
                        <pre class="bg-gray-100 p-4 rounded-md mt-2"><code>cat ~/.ssh/id_rsa.pub</code></pre>
                    </li>
                    <li class="mb-2">Copy the public key and upload it below.</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Upload SSH Public Key Form -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="bg-gray-100 px-6 py-4 rounded-t-lg">
            <h2 class="text-xl font-semibold">Upload SSH Public Key</h2>
        </div>
        <div class="p-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @if (session('ssh_key'))
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700">Uploaded SSH Key:</label>
                        <textarea class="mt-1 block w-full bg-gray-100 p-4 rounded-md" rows="5" readonly>{{ session('ssh_key') }}</textarea>
                    </div>
                @endif
            @endif

            <form action="{{ url('ssh.upload') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="ssh_key" class="block text-sm font-medium text-gray-700">Paste Your SSH Public Key Here</label>
                    <textarea name="ssh_key" class="mt-1 block w-full p-4 border border-gray-300 rounded-md shadow-sm" rows="5" required></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Upload SSH Key</button>
            </form>
        </div>
    </div>
</div>

<!-- Tab Switching Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');

        tabLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                // Remove active class from all tabs
                tabLinks.forEach(tab => tab.classList.remove('active'));
                tabContents.forEach(tab => tab.classList.add('hidden'));

                // Add active class to the clicked tab
                this.classList.add('active');
                const targetTab = document.querySelector(this.getAttribute('href'));
                targetTab.classList.remove('hidden');
            });
        });
    });
</script>
@endsection