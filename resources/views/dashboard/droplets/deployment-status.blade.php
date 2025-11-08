@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 to-gray-800 py-12 px-4 sm:px-6 lg:px-8" id="status-page" data-droplet-id="{{ $droplet_id }}">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 id="status-header" class="text-3xl font-bold text-white mb-2">
                🚀 Deployment in Progress...
            </h2>
            <p id="status-subheader" class="text-gray-400">
                Your new droplet is being provisioned. This may take several minutes.
            </p>
        </div>

        <!-- Status & Log Card -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div id="status-indicator" class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                        <span class="font-semibold text-gray-700">Status:</span>
                        <span id="status-text" class="text-gray-600">Initializing...</span>
                    </div>
                </div>
            </div>
            
            <!-- Log Console -->
            <div class="bg-gray-900 p-6">
                <label class="block text-sm font-semibold text-gray-400 mb-3">Deployment Log</label>
                <div id="log-container" class="w-full h-80 bg-black rounded-lg p-4 overflow-y-auto font-mono text-sm text-gray-300 space-y-2">
                    <p class="text-gray-500">Waiting for deployment to start...</p>
                </div>
            </div>

            <!-- Action Buttons (hidden initially) -->
            <div id="action-button-container" class="hidden p-6 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('droplets.index') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        View All Droplets
                    </a>
                </div>
            </div>
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

    //let lastMessage = '';
    let seenMessages = new Set();
    let pollingInterval;

    function addLogMessage(message) {
        if (!seenMessages.has(message)) {
            seenMessages.add(message)
            const logEntry = document.createElement('p');
            logEntry.innerHTML = `<span class="text-gray-500 mr-2">${new Date().toLocaleTimeString()}</span><span class="text-green-400">></span> ${message}`;
            logContainer.appendChild(logEntry);
            logContainer.scrollTop = logContainer.scrollHeight;
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
                statusHeader.textContent = '✅ Droplet Created Successfully!';
                statusSubheader.textContent = 'Your droplet has been provisioned and your application is deployed.';
                statusIndicator.innerHTML = '<span class="h-5 w-5 bg-green-500 rounded-full"></span><span class="font-semibold text-gray-700">Status:</span><span class="text-green-600">Completed</span>';
                actionButtonContainer.classList.remove('hidden');
            } else if (data.status === 'error') {
                clearInterval(pollingInterval);
                statusHeader.textContent = '❌ Droplet Creation  Failed';
                statusSubheader.textContent = 'An error occurred during deployment. Please check the log for details.';
                statusIndicator.innerHTML = '<span class="h-5 w-5 bg-red-500 rounded-full"></span><span class="font-semibold text-gray-700">Status:</span><span class="text-red-600">Error</span>';
                
                // Highlight last error message
                if (logContainer.lastChild) {
                    logContainer.lastChild.classList.add('text-red-400', 'font-bold');
                }
                actionButtonContainer.classList.remove('hidden');
            } else if (data.status === 'pending') {
                // Keep the spinner going for pending status
                statusText.textContent = 'Pending';
            }

        } catch (error) {
            console.error('Error polling status:', error);
            addLogMessage('⚠️ Error connecting to the server. Retrying...');
        }
    };

    // Start polling immediately, then every 5 seconds
    pollStatus();
    pollingInterval = setInterval(pollStatus, 5000);
});
</script>
@endsection