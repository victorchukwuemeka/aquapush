@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 max-w-4xl">
    <!-- Header -->
    <div class="text-center mb-8 animate-fade-in">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-red-600 mb-3 tracking-tight">
            Generate an SSH Key
        </h1>
        <p class="text-gray-600 text-base sm:text-lg max-w-xl mx-auto">
            Create a secure SSH key for your deployments with these simple steps.
        </p>
    </div>

    <!-- Instructions Section -->
    <div class="bg-white shadow-sm rounded-xl overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">How to Generate an SSH Key</h2>
        </div>
        <div class="p-6 sm:p-8">
            <!-- Tab Navigation -->
            <div class="flex flex-wrap gap-2 border-b border-gray-200 mb-6">
                <button class="tab-link px-4 py-2 text-red-600 font-medium text-sm sm:text-base border-b-2 border-red-500 hover:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200 active" data-tab="linux" aria-selected="true">
                    Linux
                </button>
                <button class="tab-link px-4 py-2 text-gray-600 font-medium text-sm sm:text-base border-b-2 border-transparent hover:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200" data-tab="mac" aria-selected="false">
                    macOS
                </button>
                <button class="tab-link px-4 py-2 text-gray-600 font-medium text-sm sm:text-base border-b-2 border-transparent hover:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200" data-tab="windows" aria-selected="false">
                    Windows
                </button>
            </div>

            <!-- Linux Instructions -->
            <div id="linux" class="tab-content">
                <div class="space-y-3">
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="true">
                            <span class="font-medium text-gray-800">1. Open a terminal</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700">
                            Launch a terminal on your Linux machine to begin the process.
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">2. Generate the SSH key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Run this command to create a new SSH key:
                            <pre class="bg-gray-100 p-4 rounded-lg text-sm font-mono mt-2"><code>ssh-keygen -t rsa -b 4096 -C "your_email@example.com"</code></pre>
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">3. Save the key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Press <kbd class="bg-gray-200 px-2 py-1 rounded-md text-sm">Enter</kbd> to save the key to the default location (<code>~/.ssh/id_rsa</code>).
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">4. Set a passphrase (optional)</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Add a passphrase for extra security when prompted (optional but recommended).
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">5. Display the public key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Run this command to view your public key:
                            <pre class="bg-gray-100 p-4 rounded-lg text-sm font-mono mt-2"><code>cat ~/.ssh/id_rsa.pub</code></pre>
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">6. Copy the key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Copy the displayed public key and use it in your deployment configuration.
                        </div>
                    </div>
                </div>
            </div>

            <!-- macOS Instructions -->
            <div id="mac" class="tab-content hidden">
                <div class="space-y-3">
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="true">
                            <span class="font-medium text-gray-800">1. Open Terminal</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700">
                            Launch the Terminal app on your Mac.
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">2. Generate the SSH key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Run this command to create a new SSH key:
                            <pre class="bg-gray-100 p-4 rounded-lg text-sm font-mono mt-2"><code>ssh-keygen -t rsa -b 4096 -C "your_email@example.com"</code></pre>
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">3. Save the key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Press <kbd class="bg-gray-200 px-2 py-1 rounded-md text-sm">Enter</kbd> to save the key to the default location (<code>~/.ssh/id_rsa</code>).
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">4. Set a passphrase (optional)</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Add a passphrase for extra security when prompted (optional but recommended).
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">5. Display the public key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Run this command to view your public key:
                            <pre class="bg-gray-100 p-4 rounded-lg text-sm font-mono mt-2"><code>cat ~/.ssh/id_rsa.pub</code></pre>
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">6. Copy the key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Copy the displayed public key and use it in your deployment configuration.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Windows Instructions -->
            <div id="windows" class="tab-content hidden">
                <div class="space-y-3">
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="true">
                            <span class="font-medium text-gray-800">1. Open Git Bash or WSL</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700">
                            Launch Git Bash or a Windows Subsystem for Linux (WSL) terminal.
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">2. Generate the SSH key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Run this command to create a new SSH key:
                            <pre class="bg-gray-100 p-4 rounded-lg text-sm font-mono mt-2"><code>ssh-keygen -t rsa -b 4096 -C "your_email@example.com"</code></pre>
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">3. Save the key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Press <kbd class="bg-gray-200 px-2 py-1 rounded-md text-sm">Enter</kbd> to save the key to the default location (<code>~/.ssh/id_rsa</code>).
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">4. Set a passphrase (optional)</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Add a passphrase for extra security when prompted (optional but recommended).
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">5. Display the public key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Run this command to view your public key:
                            <pre class="bg-gray-100 p-4 rounded-lg text-sm font-mono mt-2"><code>cat ~/.ssh/id_rsa.pub</code></pre>
                        </div>
                    </div>
                    <div class="accordion">
                        <button class="accordion-toggle w-full text-left py-3 px-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200" aria-expanded="false">
                            <span class="font-medium text-gray-800">6. Copy the key</span>
                            <svg class="w-5 h-5 inline-block float-right transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="accordion-content p-4 text-gray-700 hidden">
                            Copy the displayed public key and use it in your deployment configuration.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Tab Switching and Accordion -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tab Switching
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');

        tabLinks.forEach(link => {
            link.addEventListener('click', () => {
                tabLinks.forEach(tab => {
                    tab.classList.remove('active', 'text-red-600', 'border-red-500');
                    tab.classList.add('text-gray-600', 'border-transparent');
                    tab.setAttribute('aria-selected', 'false');
                });
                tabContents.forEach(content => content.classList.add('hidden'));

                link.classList.add('active', 'text-red-600', 'border-red-500');
                link.classList.remove('text-gray-600', 'border-transparent');
                link.setAttribute('aria-selected', 'true');
                const targetTab = document.getElementById(link.dataset.tab);
                targetTab.classList.remove('hidden');
                targetTab.classList.add('animate-fade-in');

                // Reset accordions in new tab
                const accordions = targetTab.querySelectorAll('.accordion');
                accordions.forEach((acc, index) => {
                    const content = acc.querySelector('.accordion-content');
                    const toggle = acc.querySelector('.accordion-toggle');
                    if (index === 0) {
                        content.classList.remove('hidden');
                        toggle.setAttribute('aria-expanded', 'true');
                        toggle.querySelector('svg').classList.add('rotate-180');
                    } else {
                        content.classList.add('hidden');
                        toggle.setAttribute('aria-expanded', 'false');
                        toggle.querySelector('svg').classList.remove('rotate-180');
                    }
                });
            });
        });

        // Accordion Functionality
        const accordionToggles = document.querySelectorAll('.accordion-toggle');
        accordionToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const content = toggle.nextElementSibling;
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                content.classList.toggle('hidden', isExpanded);
                toggle.setAttribute('aria-expanded', !isExpanded);
                toggle.querySelector('svg').classList.toggle('rotate-180');
            });
        });
    });
</script>

<!-- Custom CSS for Animation and Styling -->
<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .accordion-toggle svg.rotate-180 {
        transform: rotate(180deg);
    }
</style>
@endsection