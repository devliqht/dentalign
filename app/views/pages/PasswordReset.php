<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
        <div class="glass-card bg-white/90 rounded-2xl p-8 shadow-xl">
            <div class="text-center mb-8">
                <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-family-bodoni font-semibold text-nhd-blue mb-2">
                            Password Reset Successful!
                        </h1>
                        <p class="text-sm text-gray-600 mb-6">
                            Your password has been successfully updated for <strong><?php echo htmlspecialchars($user_email); ?></strong>
                        </p>
                        
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                            <p class="text-green-700 text-sm">
                                ✓ Your password has been changed successfully.<br>
                                You can now log in with your new password.
                            </p>
                        </div>
                        
                        <a href="<?php echo BASE_URL; ?>/login" class="w-full bg-nhd-blue text-white py-3 px-4 rounded-xl hover:bg-nhd-blue/90 transition-colors inline-block text-center">
                            Continue to Login
                        </a>
                        
                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-500">
                                For security reasons, you will need to log in again with your new password.
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <h1 class="text-2xl font-family-bodoni font-semibold text-nhd-blue mb-2">
                        Reset Your Password
                    </h1>
                    <p class="text-sm text-gray-600">
                        Enter your new password for <strong><?php echo htmlspecialchars($user_email); ?></strong>
                    </p>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION["error"])): ?>
                <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-red-600 text-sm"><?php echo htmlspecialchars($_SESSION["error"]); ?></p>
                </div>
                <?php unset($_SESSION["error"]); ?>
            <?php endif; ?>

            <?php if (!isset($_GET['success']) || $_GET['success'] != '1'): ?>

            <!-- Password Reset Form -->
            <form method="POST" action="<?php echo BASE_URL; ?>/reset-password" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group">
                    <label for="password" class="text-sm font-medium text-neutral-700 mb-1">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your new password" required />
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="text-sm font-medium text-neutral-700 mb-1">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" required />
                    <div id="password-match" class="mt-1 text-sm"></div>
                </div>

                <!-- Password Requirements -->
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Password Requirements:</h4>
                    <div class="space-y-1 text-xs text-gray-600">
                        <div id="req-length" class="invalid">✗ At least 8 characters long</div>
                        <div id="req-uppercase" class="invalid">✗ One uppercase letter</div>
                        <div id="req-lowercase" class="invalid">✗ One lowercase letter</div>
                        <div id="req-number" class="invalid">✗ One number</div>
                        <div id="req-special" class="invalid">✗ One special character</div>
                    </div>
                </div>
                
                <button type="submit" id="resetBtn" class="w-full bg-nhd-blue text-white py-3 rounded-xl hover:bg-nhd-blue/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Reset Password
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Remember your password? 
                    <a href="<?php echo BASE_URL; ?>/login" class="text-nhd-green hover:text-nhd-green/80 font-medium underline transition-colors">
                        Back to Login
                    </a>
                </p>
            </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .valid {
        color: #16a34a;
    }
    .invalid {
        color: #dc2626;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordMatch = document.getElementById('password-match');
    const resetBtn = document.getElementById('resetBtn');
    
    const requirements = {
        length: document.getElementById('req-length'),
        uppercase: document.getElementById('req-uppercase'),
        lowercase: document.getElementById('req-lowercase'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special')
    };

    function validatePassword(password) {
        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        for (let check in checks) {
            if (checks[check]) {
                requirements[check].classList.add('valid');
                requirements[check].classList.remove('invalid');
                requirements[check].innerHTML = requirements[check].innerHTML.replace('✗', '✓');
            } else {
                requirements[check].classList.add('invalid');
                requirements[check].classList.remove('valid');
                requirements[check].innerHTML = requirements[check].innerHTML.replace('✓', '✗');
            }
        }

        return Object.values(checks).every(check => check);
    }

    function validatePasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword === '') {
            passwordMatch.textContent = '';
            passwordMatch.className = 'mt-1 text-sm';
            return false;
        }
        
        if (password === confirmPassword) {
            passwordMatch.textContent = '✓ Passwords match';
            passwordMatch.className = 'mt-1 text-sm text-green-600';
            return true;
        } else {
            passwordMatch.textContent = '✗ Passwords do not match';
            passwordMatch.className = 'mt-1 text-sm text-red-600';
            return false;
        }
    }

    function updateSubmitButton() {
        const passwordValid = validatePassword(passwordInput.value);
        const passwordsMatch = validatePasswordMatch();
        
        resetBtn.disabled = !(passwordValid && passwordsMatch && passwordInput.value.length > 0);
    }

    passwordInput.addEventListener('input', updateSubmitButton);
    confirmPasswordInput.addEventListener('input', updateSubmitButton);
});
</script> 