@extends("layouts.app")
@section("content")
<!-- Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-red-50 via-white to-red-50">
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 relative">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-600 rounded-full text-sm font-semibold mb-8">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                </svg>
                Deploy in Minutes, Not Hours
            </div>

            <!-- Headline -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight">
                <span class="bg-gradient-to-r from-red-600 to-red-500 bg-clip-text text-transparent">
                    Deploy Laravel Apps
                </span>
                <br>
                <span class="text-gray-900">with Zero Hassle</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-xl lg:text-2xl text-gray-600 mb-10 max-w-3xl mx-auto leading-relaxed">
                Connect your GitHub repository and deploy to DigitalOcean automatically. 
                <span class="text-red-600 font-semibold">No DevOps experience required.</span>
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-12">
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" 
                       class="group bg-red-600 text-white py-4 px-10 rounded-xl hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-semibold text-lg inline-flex items-center justify-center">
                        Go to Dashboard
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('auth.redirect') }}" 
                       class="group bg-red-600 text-white py-4 px-10 rounded-xl hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-semibold text-lg inline-flex items-center justify-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                        Sign in with GitHub
                    </a>
                    <a href="#features" 
                       class="bg-white text-gray-700 py-4 px-10 rounded-xl border-2 border-gray-300 hover:border-red-600 hover:text-red-600 transition-all duration-300 font-semibold text-lg">
                        Learn More
                    </a>
                @endif
            </div>

            <!-- Social Proof -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-8 text-sm text-gray-600">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="font-semibold">Fast & Reliable</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Secure by Default</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Auto-Configured</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div id="features" class="py-20 lg:py-28 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Everything You Need to Deploy
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Built for developers who want to focus on code, not infrastructure
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            <!-- Feature 1 -->
            <div class="group p-8 border-2 border-gray-100 rounded-2xl hover:border-red-200 hover:shadow-xl transition-all duration-300 bg-gradient-to-br from-white to-gray-50">
                <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-3 text-gray-900">One-Click Deploy</h3>
                <p class="text-gray-600 leading-relaxed">
                    Connect your GitHub repository and deploy instantly. Automated setup handles all the technical details for you.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="group p-8 border-2 border-gray-100 rounded-2xl hover:border-red-200 hover:shadow-xl transition-all duration-300 bg-gradient-to-br from-white to-gray-50">
                <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-3 text-gray-900">Lightning Fast</h3>
                <p class="text-gray-600 leading-relaxed">
                    Deploy your Laravel applications to DigitalOcean in minutes. Optimized infrastructure ensures peak performance.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="group p-8 border-2 border-gray-100 rounded-2xl hover:border-red-200 hover:shadow-xl transition-all duration-300 bg-gradient-to-br from-white to-gray-50">
                <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-3 text-gray-900">Enterprise Security</h3>
                <p class="text-gray-600 leading-relaxed">
                    SSL certificates, secure database setup, and hardened server configurations included automatically.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="py-20 lg:py-28 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Deploy in Three Simple Steps
            </h2>
            <p class="text-xl text-gray-600">
                From code to production in under 5 minutes
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="space-y-8">
                <!-- Step 1 -->
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                        1
                    </div>
                    <div class="flex-grow pt-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Connect Your GitHub</h3>
                        <p class="text-gray-600 text-lg">Sign in with GitHub and select the Laravel repository you want to deploy.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                        2
                    </div>
                    <div class="flex-grow pt-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Configure Your Server</h3>
                        <p class="text-gray-600 text-lg">Choose your DigitalOcean droplet and set up your database credentials.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                        3
                    </div>
                    <div class="flex-grow pt-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Deploy & Go Live</h3>
                        <p class="text-gray-600 text-lg">Click deploy and watch your application go live automatically. That's it!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-20 lg:py-28 bg-gradient-to-r from-red-600 to-red-500">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
            Ready to Deploy Your Laravel App?
        </h2>
        <p class="text-xl text-red-100 mb-10 max-w-2xl mx-auto">
            Join developers who ship faster with AquaPush. No credit card required to get started.
        </p>
        @if(!Auth::check())
            <a href="{{ route('auth.redirect') }}" 
               class="inline-flex items-center bg-white text-red-600 py-4 px-10 rounded-xl hover:bg-gray-50 transition-all duration-300 shadow-xl font-semibold text-lg">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
                Get Started Free
            </a>
        @endif
    </div>
</div>


<style>
.bg-grid-pattern {
    background-image: 
        linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
}
</style>
@endsection