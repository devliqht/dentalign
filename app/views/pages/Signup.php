<div class="relative min-h-screen flex flex-col items-center justify-center">
    <div class="flex flex-col items-center justify-center gap-2 index-form glass-card p-6">
    <!-- <img src="<?php echo BASE_URL; ?>/public/logo.png" alt="DentAlign Logo" class="mx-auto w-[40%] rounded-full mb-2" /> -->
        <div class="flex flex-col w-full mb-4">
            <h1 class="text-3xl tracking-tight font-family-bodoni font-semibold text-nhd-blue">Register</h1>
            <p>Already have an account? <a href="<?php echo BASE_URL; ?>/login" class="underline text-nhd-green">Login here</a></p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        

        <form method="POST" action="<?php echo BASE_URL; ?>/signup" class="space-y-2 w-full" id="signup-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(
                $csrf_token
            ); ?>">
            <div class="form-row">
                <div class="form-group !mb-0 !pb-0">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                
                <div class="form-group !mb-0 !pb-0">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>
            
            <div class="form-group !mb-0">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="abc@gmail.com" required>
            </div>
            
            <div class="form-col !mb-0 !pb-0">
                <div class="form-group !mb-0">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group !mb-0">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <div id="password-match" class="mt-1 text-sm"></div>
                </div>
            </div>

                <input type="hidden" name="user_type" value="Patient">
                <style>
                    #user_type {
                        display: none;
                    }
                    label[for="user_type"] {
                        display: none;
                    }
                </style>
 
                <p class="text-sm text-gray-600 mb-1">Password must contain:</p>
                <ul class="text-xs space-y-1">
                    <li id="length" class="requirement">✗ At least 8 characters</li>
                    <li id="uppercase" class="requirement">✗ One uppercase letter</li>
                    <li id="lowercase" class="requirement">✗ One lowercase letter</li>
                    <li id="number" class="requirement">✗ One number</li>
                    <li id="special" class="requirement">✗ One special character</li>
                </ul>
            </div>
            <button type="submit" id="submit-btn">Sign Up</button>
        </form>
        

    </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const submitBtn = document.getElementById('submit-btn');
    const passwordMatch = document.getElementById('password-match');
    
    const requirements = {
        length: document.getElementById('length'),
        uppercase: document.getElementById('uppercase'),
        lowercase: document.getElementById('lowercase'),
        number: document.getElementById('number'),
        special: document.getElementById('special')
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
        
        if (passwordValid && passwordsMatch && passwordInput.value !== '') {
            submitBtn.disabled = false;
            submitBtn.className = submitBtn.className.replace('opacity-50 cursor-not-allowed', '');
        } else {
            submitBtn.disabled = true;
            if (!submitBtn.className.includes('opacity-50')) {
                submitBtn.className += ' opacity-50 cursor-not-allowed';
            }
        }
    }

    passwordInput.addEventListener('input', function() {
        validatePassword(this.value);
        updateSubmitButton();
    });

    confirmPasswordInput.addEventListener('input', function() {
        validatePasswordMatch();
        updateSubmitButton();
    });

    // Initial state
    updateSubmitButton();
});
</script>

<style>
.password-requirements {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 0.75rem;
}

.requirement {
    transition: color 0.3s ease;
    color: #6b7280;
}

.requirement.valid {
    color: #10b981;
}

.requirement.invalid {
    color: #ef4444;
}

.form-group {
    margin-bottom: 1rem;
}

.form-col {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (min-width: 768px) {
    .form-col {
        flex-direction: row;
    }
    
    .form-col .form-group {
        flex: 1;
    }
}
</style>