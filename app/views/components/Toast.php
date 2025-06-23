<!-- Toast Container -->
<div id="toast-container">
    <!-- Toasts will be dynamically inserted here -->
</div>

<!-- Toast Template (hidden, used for cloning) -->
<div id="toast-template" class="toast-notification glass-card bg-white/40 rounded-2xl shadow-lg p-4" style="display: none; min-width: 280px; max-width: 350px;">
    <div class="flex items-start">
        <!-- Icon -->
        <div class="toast-icon flex-shrink-0 mr-3 mt-0.5">
            <!-- Success Icon -->
            <svg class="toast-success-icon w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <!-- Error Icon -->
            <svg class="toast-error-icon w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
        </div>
        
        <!-- Content -->
        <div class="flex-1">
            <div class="toast-message text-sm font-medium text-gray-900">
                <!-- Message content will be inserted here -->
            </div>
        </div>
        
        <!-- Close Button -->
        <button class="bg-transparent border-none" onclick="closeToast(this)">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <!-- Progress Bar -->
    <div class="toast-progress-container mt-3">
        <div class="toast-progress-bar bg-gray-200 rounded-full h-1">
            <div class="toast-progress-fill h-1 rounded-full transition-all duration-linear"></div>
        </div>
    </div>
</div> 