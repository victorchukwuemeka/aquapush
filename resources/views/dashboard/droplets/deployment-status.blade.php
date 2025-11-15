@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8" id="status-page" data-droplet-id="{{ $droplet_id }}">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                <div id="header-icon" class="animate-spin rounded-full h-10 w-10 border-4 border-red-600 border-t-transparent"></div>
            </div>
            <h1 id="status-header" class="text-4xl font-extrabold text-gray-900 mb-3">
                🚀 Provisioning Your Droplet
            </h1>
            <p id="status-subheader" class="text-lg text-gray-600 max-w-2xl mx-auto">
                Hang tight! We're setting up your DigitalOcean droplet with all the essentials. This usually takes 2-5 minutes.
            </p>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200 mb-6">
            <!-- Status Bar -->
            <div class="bg-gradient-to-r from-red-50 to-white p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div id="status-indicator" class="flex items-center space-x-3">
                        <div class="animate-pulse">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Current Status</span>
                        <span id="status-text" class="text-lg font-bold text-red-600">Initializing...</span>
                    </div>
                    <div id="timer" class="text-sm text-gray-500 font-mono">
                        <span id="elapsed-time">00:00</span>
                    </div>
                </div>
            </div>
            
            <!-- Progress Visualization -->
            <div class="px-6 py-4 bg-gray-50">
                <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
                    <span>Progress</span>
                    <span id="progress-percent">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                    <div id="progress-bar" class="bg-gradient-to-r from-red-500 to-red-600 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                </div>
            </div>

            <!-- Log Console -->
            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Deployment Log
                    </label>
                    <button onclick="scrollToBottom()" class="text-xs text-gray-500 hover:text-red-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </button>
                </div>
                <div id="log-container" class="w-full h-96 bg-gray-900 rounded-xl p-5 overflow-y-auto font-mono text-sm shadow-inner border border-gray-700">
                    <div class="space-y-1">
                        <p class="text-gray-500 animate-pulse">⏳ Waiting for deployment to start...</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (hidden initially) -->
            <div id="action-button-container" class="hidden p-6 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('droplet.show', $droplet_id) }}" 

                       class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Droplet Details
                    </a>
                    <a href="{{ route('droplets.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200 border-2 border-gray-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        View All Droplets
                    </a>
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                What's Happening?
            </h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start">
                    <span class="text-red-600 mr-2">•</span>
                    <span>Creating your DigitalOcean droplet with optimized settings</span>
                </li>
                <li class="flex items-start">
                    <span class="text-red-600 mr-2">•</span>
                    <span>Installing LEMP stack (Apache2, MySQL, PHP)</span>
                </li>
                <!--<li class="flex items-start">
                    <span class="text-red-600 mr-2">•</span>
                    <span>Configuring security settings and firewall</span>
                </li>-->
                <li class="flex items-start">
                    <span class="text-red-600 mr-2">•</span>
                    <span>Setting up your database and environment</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.getElementById('status-page');
    const dropletId = page.dataset.dropletId;

    const logContainer = document.getElementById('log-container');
    const statusText = document.getElementById('status-text');
    const statusHeader = document.getElementById('status-header');
    const statusSubheader = document.getElementById('status-subheader');
    const statusIndicator = document.getElementById('status-indicator');
    const actionButtonContainer = document.getElementById('action-button-container');
    const headerIcon = document.getElementById('header-icon');
    const progressBar = document.getElementById('progress-bar');
    const progressPercent = document.getElementById('progress-percent');

    let seenMessages = new Set();
    let pollingInterval;
    let startTime = Date.now();
    let progress = 0;

    // Timer
    setInterval(() => {
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
        const seconds = (elapsed % 60).toString().padStart(2, '0');
        document.getElementById('elapsed-time').textContent = `${minutes}:${seconds}`;
    }, 1000);

    function scrollToBottom() {
        logContainer.scrollTop = logContainer.scrollHeight;
    }

    function updateProgress(value) {
        progress = Math.min(value, 100);
        progressBar.style.width = `${progress}%`;
        progressPercent.textContent = `${progress}%`;
    }

    function addLogMessage(message) {
        if (!seenMessages.has(message)) {
            seenMessages.add(message);
            
            // Clear initial placeholder
            if (logContainer.children.length === 1 && logContainer.textContent.includes('Waiting for deployment')) {
                logContainer.innerHTML = '';
            }
            
            const logEntry = document.createElement('div');
            logEntry.className = 'flex items-start space-x-2 animate-fade-in';
            logEntry.innerHTML = `
                <span class="text-gray-500 text-xs mt-0.5">${new Date().toLocaleTimeString()}</span>
                <span class="text-red-500">▸</span>
                <span class="text-gray-300 flex-1">${message}</span>
            `;
            logContainer.appendChild(logEntry);
            scrollToBottom();

            // Update progress based on keywords
            if (message.includes('Creating droplet')) updateProgress(10);
            if (message.includes('Installing')) updateProgress(30);
            if (message.includes('Configuring')) updateProgress(50);
            if (message.includes('Setting up')) updateProgress(70);
            if (message.includes('Finalizing')) updateProgress(90);
        }
    }

    const pollStatus = async () => {
        try {
            const response = await fetch(`/droplets/${dropletId}/status`);
            const data = await response.json();

            statusText.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            addLogMessage(data.message);

            if (data.status === 'success') {
                clearInterval(pollingInterval);
                updateProgress(100);
                
                statusHeader.textContent = '✅ Droplet Ready!';
                statusSubheader.textContent = 'Your droplet has been successfully provisioned and is ready to deploy applications.';
                
                headerIcon.className = 'w-10 h-10 text-green-500';
                headerIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
                headerIcon.parentElement.className = 'inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4';
                
                statusIndicator.innerHTML = `
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Current Status</span>
                    <span class="text-lg font-bold text-green-600">Completed</span>
                `;
                
                actionButtonContainer.classList.remove('hidden');
                
            } else if (data.status === 'error') {
                clearInterval(pollingInterval);
                
                statusHeader.textContent = '❌ Deployment Failed';
                statusSubheader.textContent = 'Something went wrong during droplet creation. Please review the logs below.';
                
                headerIcon.className = 'w-10 h-10 text-red-500';
                headerIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>';
                headerIcon.parentElement.className = 'inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4';
                
                statusIndicator.innerHTML = `
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Current Status</span>
                    <span class="text-lg font-bold text-red-600">Failed</span>
                `;
                
                progressBar.className = 'bg-red-500 h-2.5 rounded-full transition-all duration-500 ease-out';
                
                if (logContainer.lastChild) {
                    logContainer.lastChild.classList.add('text-red-400', 'font-bold');
                }
                
                actionButtonContainer.classList.remove('hidden');
                
            } else if (data.status === 'pending') {
                statusText.textContent = 'In Progress';
            }

        } catch (error) {
            console.error('Error polling status:', error);
            addLogMessage('⚠️ Connection issue. Retrying...');
        }
    };

    // Start polling
    pollStatus();
    pollingInterval = setInterval(pollStatus, 5000);
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection