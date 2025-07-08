<header class="fixed top-0 left-0 right-0 z-50 p-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <img src="<?php echo BASE_URL; ?>/public/logo.png" alt="North Hill Dental" class="h-10 w-auto">
                <span class="ml-3 text-xl font-family-bodoni font-bold text-nhd-blue">North Hill Dental</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <button onclick="openLoginModal()" class="glass-card bg-white/80 text-gray-800 font-medium transition-colors text-sm px-3 py-2">
                    Log In
                </button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/signup'" class="glass-card bg-white/80 text-gray-800 font-medium transition-colors text-sm px-3 py-2">
                    Sign Up
                </button>
            </div>
            
            <div class="md:hidden">
                <button onclick="toggleMobileMenu()" class="text-gray-700 hover:text-nhd-blue">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
        <div class="px-4 py-3 space-y-3">
            <a href="#about" class="block text-gray-700 hover:text-nhd-blue">About Us</a>
            <a href="#services" class="block text-gray-700 hover:text-nhd-blue">Our Services</a>
            <a href="#dentists" class="block text-gray-700 hover:text-nhd-blue">Our Dentists</a>
            <a href="#contact" class="block text-gray-700 hover:text-nhd-blue">Contact</a>
            <div class="pt-3 border-t border-gray-200">
                <button onclick="openLoginModal()" class="block w-full text-left text-nhd-blue font-medium">Log In</button>
                <a href="<?php echo BASE_URL; ?>/signup" class="block mt-2 bg-nhd-blue text-white px-4 py-2 rounded-xl text-center">Sign Up</a>
            </div>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="text-center lg:text-left">
                <h1 class="text-4xl lg:text-6xl font-family-bodoni font-bold text-nhd-blue mb-6 leading-tight">
                    Your Smile is Our <span class="text-nhd-brown">Priority</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Experience exceptional dental care at North Hill Dental. Our team of expert dentists provides comprehensive, personalized treatment in a comfortable, modern environment.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <button onclick="openLoginModal()" class="glass-card bg-nhd-blue/80 text-white px-6 py-4 rounded-2xl text-center text-md font-medium font-family-sans hover:bg-nhd-blue/90 transition-all duration-300 hover:shadow-xl">
                        Access Patient Portal
                    </button>
                    <a href="#services" class="border-1 border-nhd-brown text-nhd-brown px-6 py-4 text-center rounded-2xl text-md font-semibold hover:bg-nhd-brown hover:text-white transition-all duration-300">
                        Learn More
                    </a>
                </div>
            </div>
            
            <!-- Dentist Image -->
            <div class="relative">
                <div class="relative z-10">
                    <img src="https://od2-image-api.abs-cbn.com/prod/editorImage/175064127425520250623-Forsaken.jpg" 
                         alt="Professional Dentist" 
                         class="rounded-2xl shadow-2xl w-full max-w-md mx-auto">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Us Section -->
<section id="about" class="py-20 bg-nhd-pale">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-family-bodoni font-bold text-nhd-blue mb-4">About North Hill Dental</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                For over a decade, we've been providing exceptional dental care to families in Canlaon City and surrounding communities.
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-nhd-blue/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Quality Care</h3>
                <p class="text-gray-600">State-of-the-art equipment and proven techniques for the best results.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-nhd-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-nhd-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Compassionate Team</h3>
                <p class="text-gray-600">Our caring staff ensures your comfort throughout every visit.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-nhd-blue/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Convenient Hours</h3>
                <p class="text-gray-600">Flexible scheduling to fit your busy lifestyle.</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-family-bodoni font-bold text-nhd-blue mb-4">Our Services</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Comprehensive dental care for the whole family, from routine cleanings to advanced treatments.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 bg-nhd-green/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-nhd-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Preventive Care</h3>
                <p class="text-gray-600">Regular cleanings, exams, and preventive treatments to keep your smile healthy.</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 bg-nhd-blue/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Restorative Dentistry</h3>
                <p class="text-gray-600">Fillings, crowns, bridges, and other treatments to restore your oral health.</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 bg-nhd-green/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-nhd-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Cosmetic Dentistry</h3>
                <p class="text-gray-600">Teeth whitening, veneers, and smile makeovers to enhance your appearance.</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 bg-nhd-blue/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Orthodontics</h3>
                <p class="text-gray-600">Braces and clear aligners to straighten teeth and improve your bite.</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 bg-nhd-green/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-nhd-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Oral Surgery</h3>
                <p class="text-gray-600">Extractions, implants, and surgical procedures performed with precision.</p>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="w-12 h-12 bg-nhd-blue/10 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Pediatric Care</h3>
                <p class="text-gray-600">Gentle, specialized care for children to ensure healthy smiles from an early age.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-family-bodoni font-bold text-nhd-blue mb-4">Visit Our Clinic</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Conveniently located in Canlaon City with ample parking and modern facilities.
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-nhd-blue/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Location</h3>
                <p class="text-gray-600">Barangay Panubigan<br>Canlaon City, Philippines</p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-nhd-green/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-nhd-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Office Hours</h3>
                <p class="text-gray-600">Monday - Friday<br>8:00 AM - 6:00 PM</p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-nhd-blue/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-family-bodoni font-semibold text-nhd-blue mb-2">Emergency Contact</h3>
                <p class="text-gray-600">24/7 Hotline<br>0927 508 6540</p>
            </div>
        </div>
    </div>
</section>

<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 bg-black/20 backdrop-blur-[4px] z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card bg-white/90 rounded-2xl p-8 w-full max-w-md relative">
        <button onclick="closeLoginModal()" class="absolute top-4 right-4 glass-card text-gray-800 rounded-full p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="text-center mb-6">
            <h2 class="text-2xl font-family-bodoni font-semibold text-nhd-blue mb-2">
                Sign In to Your Account
            </h2>
            <p class="text-sm text-gray-600">
                Access your patient portal or staff dashboard
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-red-600 text-sm"><?php echo htmlspecialchars(
                    $error
                ); ?></p>
            </div>
        <?php endif; ?>


        
        <form method="POST" action="<?php echo BASE_URL; ?>/login" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(
                $csrf_token
            ); ?>">
            
            <div class="form-group">
                <input type="email" id="email" name="email" placeholder="Enter your email" required />
                <input type="password" id="password" name="password" placeholder="Enter your password" required />
            </div>
            <button type="submit" class="w-full bg-nhd-blue text-white py-3 rounded-xl hover:bg-nhd-blue/90 transition-colors">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center flex flex-col gap-4">
            <p class="text-sm text-gray-600">
                New patient? 
                <a href="<?php echo BASE_URL; ?>/signup" class="text-nhd-green hover:text-nhd-green/80 font-medium underline transition-colors">
                    Create an account
                </a>
            </p>
            <p class="text-sm text-gray-600">
                Forgot your password? 
                <a href="#" onclick="openResetPasswordModal(); return false;" class="text-nhd-green hover:text-nhd-green/80 font-medium underline transition-colors">
                   Reset here 
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black/20 backdrop-blur-[4px] z-50 hidden flex items-center justify-center p-4">
    <div class="glass-card bg-white/90 rounded-2xl p-8 w-full max-w-md relative">
        <button onclick="closeResetPasswordModal()" class="absolute top-4 right-4 glass-card text-gray-800 rounded-full p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="text-center mb-6">
            <h2 class="text-2xl font-family-bodoni font-semibold text-nhd-blue mb-2">
                Reset Your Password
            </h2>
            <p class="text-sm text-gray-600">
                Enter your email address to receive a password reset link.
            </p>
        </div>

        <form id="resetPasswordForm" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(
                $csrf_token
            ); ?>">
            
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
                Back to 
                <a href="#" onclick="closeResetPasswordModal(); openLoginModal(); return false;" class="text-nhd-green hover:text-nhd-green/80 font-medium underline transition-colors">
                    Login
                </a>
            </p>
        </div>
    </div>
</div>

<script>
function openLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openResetPasswordModal() {
    document.getElementById('resetPasswordModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeResetPasswordModal() {
    document.getElementById('resetPasswordModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    document.getElementById('resetPasswordForm').reset();
    document.getElementById('resetMessage').classList.add('hidden');
    document.getElementById('resetPasswordBtn').disabled = false;
    document.getElementById('resetPasswordBtn').textContent = 'Send Reset Link';
}

function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
}

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

document.querySelectorAll('#mobile-menu a').forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.add('hidden');
    });
});

<?php if (!empty($error)): ?>
    document.addEventListener('DOMContentLoaded', function() {
        openLoginModal();
    });
<?php endif; ?>

// Handle password reset form submission
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