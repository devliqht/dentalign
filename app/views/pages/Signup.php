<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Signup Form -->
            <div class="order-2 lg:order-1">
                <div class="glass-card bg-white/90 rounded-2xl p-8 max-w-md mx-auto lg:mx-0">
                    <div class="flex flex-col w-full mb-6">
                        <h1 class="text-3xl tracking-tight font-family-sans font-semibold text-nhd-blue mb-2">Join Us</h1>
                        <p class="text-gray-600">Already have an account? <a href="<?php echo BASE_URL; ?>/login" class="underline text-nhd-green hover:text-nhd-blue transition-colors">Login here</a></p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo BASE_URL; ?>/signup" class="space-y-4" id="signup-form">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(
                            $csrf_token
                        ); ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="first_name" name="first_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="last_name" name="last_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" placeholder="abc@gmail.com" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="password" name="password" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                            <div id="password-match" class="mt-2 text-sm"></div>
                        </div>

                        <input type="hidden" name="user_type" value="Patient">

                        <div class="password-requirements bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-700 mb-2 font-medium">Password Requirements:</p>
                            <ul class="text-xs space-y-1">
                                <li id="length" class="requirement flex items-center">
                                    <span class="mr-2">✗</span> At least 8 characters
                                </li>
                                <li id="uppercase" class="requirement flex items-center">
                                    <span class="mr-2">✗</span> One uppercase letter
                                </li>
                                <li id="lowercase" class="requirement flex items-center">
                                    <span class="mr-2">✗</span> One lowercase letter
                                </li>
                                <li id="number" class="requirement flex items-center">
                                    <span class="mr-2">✗</span> One number
                                </li>
                                <li id="special" class="requirement flex items-center">
                                    <span class="mr-2">✗</span> One special character
                                </li>
                            </ul>
                        </div>

                        <button type="submit" id="submit-btn" 
                                class="w-full glass-card bg-nhd-blue text-white px-6 py-4 rounded-xl text-center text-md font-medium hover:bg-nhd-blue/90 transition-all duration-300 hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            Create Account
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Dentist Image -->
            <div class="order-1 lg:order-2 relative">
                <div class="relative z-10">
                    <img src="https://od2-image-api.abs-cbn.com/prod/editorImage/175064127425520250623-Forsaken.jpg" 
                         alt="Professional Dentist" 
                         class="rounded-2xl shadow-2xl w-full max-w-md mx-auto">
                </div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 -z-10">
                    <div class="w-96 h-96 bg-nhd-blue/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>
</section>


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
            const element = requirements[check];
            const iconSpan = element.querySelector('span');
            
            if (checks[check]) {
                element.classList.add('valid');
                element.classList.remove('invalid');
                iconSpan.textContent = '✓';
            } else {
                element.classList.add('invalid');
                element.classList.remove('valid');
                iconSpan.textContent = '✗';
            }
        }

        return Object.values(checks).every(check => check);
    }

    function validatePasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword === '') {
            passwordMatch.textContent = '';
            passwordMatch.className = 'mt-2 text-sm';
            return false;
        }
        
        if (password === confirmPassword) {
            passwordMatch.textContent = '✓ Passwords match';
            passwordMatch.className = 'mt-2 text-sm text-green-600';
            return true;
        } else {
            passwordMatch.textContent = '✗ Passwords do not match';
            passwordMatch.className = 'mt-2 text-sm text-red-600';
            return false;
        }
    }

    function updateSubmitButton() {
        const passwordValid = validatePassword(passwordInput.value);
        const passwordsMatch = validatePasswordMatch();
        
        if (passwordValid && passwordsMatch && passwordInput.value !== '') {
            submitBtn.disabled = false;
            submitBtn.className = submitBtn.className.replace(' opacity-50 cursor-not-allowed', '');
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

    updateSubmitButton();
});
</script>

<style>
.password-requirements {
    background-color: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 1rem;
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
    margin-bottom: 0;
}

.glass-card {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Custom focus styles for inputs */
input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Background pattern */
body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    background-attachment: fixed;
    min-height: 100vh;
}

section {
    position: relative;
}

section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
    pointer-events: none;
}
</style>