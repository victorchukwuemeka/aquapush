@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 max-w-4xl">
    <!-- Header Section -->
    <div class="text-center mb-10 animate-fade-in">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-red-600 mb-4 tracking-tight">
            Get in Touch with AquaPush
        </h1>
        <p class="text-gray-600 text-base sm:text-lg max-w-2xl mx-auto">
            Have questions about deploying your Laravel app? Need support? We’re here to help! Reach out via the form below or explore our resources.
        </p>
    </div>

    <!-- Contact Form -->
    <section class="mb-12">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden animate-slide-up">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Send Us a Message</h2>
            </div>
            <div class="p-6 sm:p-8">
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Your name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                            required
                            aria-describedby="name_error"
                        >
                        @error('name')
                            <p id="name_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="your.email@example.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                            required
                            aria-describedby="email_error"
                        >
                        @error('email')
                            <p id="email_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-gray-700 font-medium mb-2">Subject</label>
                        <select
                            id="subject"
                            name="subject"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                            required
                            aria-describedby="subject_error"
                        >
                            <option value="" {{ old('subject') ? '' : 'selected' }}>-- Select Subject --</option>
                            <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Support</option>
                            <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback</option>
                            <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                        </select>
                        @error('subject')
                            <p id="subject_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-gray-700 font-medium mb-2">Message</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="5"
                            placeholder="Tell us how we can help..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200"
                            required
                            aria-describedby="message_error"
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p id="message_error" class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button
                            type="submit"
                            class="bg-red-600 text-white py-3 px-8 rounded-lg hover:bg-red-700 hover:shadow-lg transition-all duration-300 shadow-md text-base sm:text-lg font-medium"
                        >
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="mb-12">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden animate-slide-up">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Contact Information</h2>
            </div>
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12l-4-4m0 0l-4 4m4-4v12" />
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Email Us</h3>
                            <p class="text-gray-600 text-sm">
                                <a href="mailto:support@aquapush.com" class="text-red-600 hover:underline">support@aquapush.com</a>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Follow Us</h3>
                            <p class="text-gray-600 text-sm">
                                <a href="https://x.com/Aquapush_" target="_blank" class="text-red-600 hover:underline">X</a> |
                                <a href="https://github.com/aquapush" target="_blank" class="text-red-600 hover:underline">GitHub</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Links -->
    <section class="text-center animate-fade-in">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-4">Need Help?</h2>
        <p class="text-gray-600 text-base sm:text-lg mb-6 max-w-xl mx-auto">
            Explore our resources or start deploying your app today.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            @if(Route::has('deployments.create'))
                <a href="{{ route('deployments.create') }}"
                   class="inline-block bg-red-600 text-white py-2 px-6 rounded-lg hover:bg-red-700 hover:shadow-lg transition-all duration-300 text-sm sm:text-base font-medium">
                    Deploy Your App
                </a>
            @else
                <p class="text-red-600 text-sm">Deployment route not defined.</p>
            @endif
            @if(Route::has('get-ssh'))
                <a href="{{ route('get-ssh') }}"
                   class="inline-block bg-gray-600 text-white py-2 px-6 rounded-lg hover:bg-gray-700 hover:shadow-lg transition-all duration-300 text-sm sm:text-base font-medium">
                    SSH Key Guide
                </a>
            @else
                <p class="text-red-600 text-sm">SSH guide route not defined.</p>
            @endif
            @if(Route::has('dashboard'))
                <a href="{{ route('dashboard') }}"
                   class="inline-block bg-gray-600 text-white py-2 px-6 rounded-lg hover:bg-gray-700 hover:shadow-lg transition-all duration-300 text-sm sm:text-base font-medium">
                    View Dashboard
                </a>
            @else
                <p class="text-red-600 text-sm">Dashboard route not defined.</p>
            @endif
        </div>
    </section>

    <!-- Success Modal -->
    <div id="success-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-green-500 w-12 h-12 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400" id="success-message">
                        @if(session('success'))
                            {{ session('success') }}
                        @endif
                    </h3>
                    <button data-modal-hide="success-modal" type="button" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Show modal if success session exists
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message').textContent.trim();
        if (successMessage) {
            const modal = document.getElementById('success-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Close modal when clicking the close button
        document.querySelector('[data-modal-hide="success-modal"]').addEventListener('click', function() {
            const modal = document.getElementById('success-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });
</script>

<!-- Custom CSS for Animations -->
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    .animate-slide-up {
        animation: slideUp 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection