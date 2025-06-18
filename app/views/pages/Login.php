<div class="relative min-h-screen bg-gradient-to-br from-nhd-pale/30 to-white">
    <div class="absolute inset-0 bg-gradient-to-r from-nhd-blue/5 to-nhd-green/5"></div>
    
    <div class="relative flex flex-col items-center justify-center min-h-screen px-4 py-12">
        <div class="w-full max-w-4xl mx-auto">
            
            <div class="text-center mb-12">
                <h1 class="text-2xl md:lg:text-5xl font-family-bodoni font-bold text-nhd-blue mb-4" style="text-shadow: 1px 1px 4px rgba(20,62,121,0.18), 0 2px 8px rgba(0,0,0,0.10);">
                    Welcome to North Hill Dental
                </h1>
                <p class="bg-white/70 backdrop-blur-sm text-xl md:lg:text-3xl text-nhd-green italic font-family-bodoni max-w-2xl mx-auto leading-relaxed" style="text-shadow: 1px 1px 4px rgba(141,167,51,0.18), 0 2px 8px rgba(0,0,0,0.10);">
                    where your smile matters.
                </p>
            </div>
            <div class="flex flex-col lg:flex-row gap-8 items-center justify-center">
                <div class="w-full max-w-md">
                    <div class="bg-white/40 backdrop-blur-sm rounded-2xl p-6 border border-nhd-green/20">
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-family-bodoni font-semibold text-nhd-blue mb-2">
                                Sign In to Your Account
                            </h2>
                            <p class="text-sm text-neutral-600">
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
                                <label for="email" class="text-sm font-medium text-neutral-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="Enter your email" required />
                            </div>
                            
                            <div class="form-group">
                                <label for="password" class="text-sm font-medium text-neutral-700 mb-1">Password</label>
                                <input type="password" id="password" name="password" placeholder="Enter your password" required />
                            </div>
                            
                            <button type="submit">
                                Sign In
                            </button>
                        </form>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-neutral-600">
                                New patient? 
                                <a href="<?php echo BASE_URL; ?>/signup" class="text-nhd-green hover:text-nhd-green/80 font-medium underline transition-colors">
                                    Create an account
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="w-full max-w-md space-y-6">
                    <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-nhd-green/20">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-nhd-green/10 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-nhd-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-family-bodoni font-semibold text-nhd-blue">For Patients</h3>
                        </div>
                        <ul class="space-y-2 text-sm text-neutral-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-nhd-green mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Book and manage appointments
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-nhd-green mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                View treatment history
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-nhd-green mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Access test results
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-nhd-green mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Handle payments online
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-nhd-blue/20">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-nhd-blue/10 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-family-bodoni font-semibold text-nhd-blue">For Doctors & Staff</h3>
                        </div>
                        <ul class="space-y-2 text-sm text-neutral-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-nhd-blue mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Manage patient records
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-nhd-blue mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Schedule appointments
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-nhd-blue mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Access clinic dashboard
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-nhd-blue mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Generate reports
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-nhd-blue/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-family-bodoni font-semibold text-nhd-blue mb-1">Convenient Hours</h4>
                        <p class="text-sm text-neutral-600">Open Monday-Friday<br>8:00 AM - 6:00 PM</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-nhd-green/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-nhd-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-family-bodoni font-semibold text-nhd-blue mb-1">Location</h4>
                        <p class="text-sm text-neutral-600">Barangay Panubigan<br>Canlaon City, Philippines</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-nhd-blue/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <h4 class="font-family-bodoni font-semibold text-nhd-blue mb-1">24/7 Support</h4>
                        <p class="text-sm text-neutral-600">Emergency hotline<br>0927 508 6540</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 