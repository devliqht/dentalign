<!-- Password Reset Error Page -->
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
        <div class="glass-card bg-white/90 rounded-2xl p-8 shadow-xl text-center">
            <div class="mb-8">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-family-bodoni font-semibold text-red-600 mb-2">
                    Invalid Reset Link
                </h1>
            </div>

            <div class="mb-8">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <p class="text-red-700 text-sm">
                        <?php echo htmlspecialchars($error); ?>
                    </p>
                </div>
                
                <p class="text-gray-600 text-sm">
                    This could happen if:
                </p>
                <ul class="text-left text-gray-600 text-sm mt-2 space-y-1">
                    <li>• The link has expired (links are valid for 1 hour)</li>
                    <li>• The link has already been used</li>
                    <li>• The link was copied incorrectly</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="openPasswordResetModal()" class="w-full bg-nhd-blue text-white py-3 rounded-xl hover:bg-nhd-blue/90 transition-colors">
                    Request New Reset Link
                </button>
                
                <a href="<?php echo BASE_URL; ?>/login" class="block w-full text-center border border-gray-300 text-gray-700 py-3 rounded-xl hover:bg-gray-50 transition-colors">
                    Back to Login
                </a>
            </div>

            <!-- Contact Support -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Need help? Contact us at 
                    <a href="mailto:info@northhilldental.ph" class="text-nhd-green hover:text-nhd-green/80 underline">
                        info@northhilldental.ph
                    </a>
                    or call 0927 508 6540
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal (same as login page) -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black/20 backdrop-blur-[4px] z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card bg-white/90 rounded-2xl p-8 w-full max-w-md relative">
        <button onclick="closePasswordResetModal()" class="absolute top-4 right-4 glass-card text-gray-800 rounded-full p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="text-center mb-6">
            <h2 class="text-2xl font-family-bodoni font-semibold text-nhd-blue mb-2">
                Reset Your Password
            </h2>
            <p class="text-sm text-gray-600">
                Enter your email address to receive a new password reset link.
            </p>
        </div>

        <form id="resetPasswordForm" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            
            <div class="form-group">
                <input type="email" id="reset_email" name="email" placeholder="Enter your email" required />
            </div>
            <button type="submit" id="resetPasswordBtn" class="w-full bg-nhd-blue text-white py-3 rounded-xl hover:bg-nhd-blue/90 transition-colors">
                Send Reset Link
            </button>
        </form>

        <div id="resetMessage" class="mt-4 p-3 rounded-xl hidden">
            <p id="resetMessageText" class="text-sm"></p>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                <a href="<?php echo BASE_URL; ?>/login" class="text-nhd-green hover:text-nhd-green/80 font-medium underline transition-colors">
                    Back to Login
                </a>
            </p>
        </div>
    </div>
</div>

<script>
function openPasswordResetModal() {
    document.getElementById('resetPasswordModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePasswordResetModal() {
    document.getElementById('resetPasswordModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    document.getElementById('resetPasswordForm').reset();
    document.getElementById('resetMessage').classList.add('hidden');
    document.getElementById('resetPasswordBtn').disabled = false;
    document.getElementById('resetPasswordBtn').textContent = 'Send Reset Link';
}

document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.getElementById('resetPasswordForm');
    const resetBtn = document.getElementById('resetPasswordBtn');
    const resetMessage = document.getElementById('resetMessage');
    const resetMessageText = document.getElementById('resetMessageText');
    
    if (resetForm) {
        resetForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(resetForm);
            
            resetBtn.disabled = true;
            resetBtn.textContent = 'Sending...';
            resetMessage.classList.add('hidden');
            
            fetch('<?php echo BASE_URL; ?>/request-password-reset', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                resetBtn.disabled = false;
                resetBtn.textContent = 'Send Reset Link';
                
                if (data.success) {
                    resetMessage.className = 'mt-4 p-3 rounded-xl bg-green-50 border border-green-200';
                    resetMessageText.className = 'text-sm text-green-600';
                    resetMessageText.textContent = data.message;
                    resetMessage.classList.remove('hidden');
                    
                    resetForm.reset();
                } else {
                    resetMessage.className = 'mt-4 p-3 rounded-xl bg-red-50 border border-red-200';
                    resetMessageText.className = 'text-sm text-red-600';
                    resetMessageText.textContent = data.message;
                    resetMessage.classList.remove('hidden');
                }
            })
            .catch(error => {
                resetBtn.disabled = false;
                resetBtn.textContent = 'Send Reset Link';
                resetMessage.className = 'mt-4 p-3 rounded-xl bg-red-50 border border-red-200';
                resetMessageText.className = 'text-sm text-red-600';
                resetMessageText.textContent = 'An error occurred. Please try again.';
                resetMessage.classList.remove('hidden');
                console.error('Error:', error);
            });
        });
    }
});
</script> 