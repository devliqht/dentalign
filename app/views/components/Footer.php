 <!-- FOOTER -->
 <?php if (!isset($hideFooter) || !$hideFooter): ?>
                <footer class="bg-nhd-blue rounded-none text-white">
                    <div class="max-w-full mx-auto px-6 py-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            
                            <div class="lg:col-span-2">
                                <div class="flex items-center mb-4">
                                    <img src="<?php echo BASE_URL; ?>/public/logo.png" alt="North Hill Dental Logo" class="w-12 h-12 rounded-full mr-3" />
                                    <h3 class="text-xl font-family-bodoni font-semibold">North Hill Dental</h3>
                                </div>
                                <p class="text-gray-300 mb-4 leading-relaxed">
                                    Your trusted partner in dental health and beautiful smiles. 
                                    We provide comprehensive dental care in a comfortable, modern environment.
                                </p>
                                <div class="flex space-x-4">
                                    <a href="https://www.facebook.com/northhilldentalcanlaon" target='_blank' class="text-gray-300 hover:text-nhd-green transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <a href="https://x.com/northhilldentalcanlaon" target='_blank' class="text-gray-300 hover:text-nhd-green transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                        </svg>
                                    </a>
                                    <a href="https://ph.pinterest.com/northhilldentalcanlaon" target='_blank' class="text-gray-300 hover:text-nhd-green transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378 0 0-.599 2.282-.744 2.840-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24.001 12.017 24.001c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-lg font-family-bodoni font-semibold mb-4">Quick Links</h4>
                                <ul class="space-y-2">
                                    <li><a href="<?php echo BASE_URL; ?>/login" class="text-gray-300 hover:text-nhd-green transition-colors">Home</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/login#about" class="text-gray-300 hover:text-nhd-green transition-colors">About Us</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/login#services" class="text-gray-300 hover:text-nhd-green transition-colors">Services</a></li>
                                    <li><a href="#" onclick="handleBookAppointment(); return false;" class="text-gray-300 hover:text-nhd-green transition-colors">Book Appointment</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/login#contact" class="text-gray-300 hover:text-nhd-green transition-colors">Contact</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/login#contact" class="text-gray-300 hover:text-nhd-green transition-colors">Emergency Care</a></li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="text-lg font-family-bodoni font-semibold mb-4">Contact Info</h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-nhd-green mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div class="text-gray-300 text-sm">
                                        	<p>Barangay Panubigan</p>
                                         	<p>Canlaon City, Philippines</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-nhd-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <a href="tel:09275086540" class="text-gray-300 hover:text-nhd-green transition-colors text-sm">0927 508 6540</a>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-nhd-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <a href="mailto:info@northhilldental.ph" class="text-gray-300 hover:text-nhd-green transition-colors text-sm">info@northhilldental.ph</a>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-nhd-green mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-gray-300 text-sm">
                                            <p>Monday - Friday</p>
                                            <p>8:00 AM - 6:00 PM</p>
                                            <p class="text-nhd-green font-medium mt-1">Emergency: 24/7</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-nhd-blue/20 py-6 mt-8">
                            <div class="flex flex-col md:flex-row justify-between items-center">
                                <p class="text-gray-300 text-sm">
                                    © <?php echo date(
                                        "Y"
                                    ); ?> North Hill Dental. All rights reserved.
                                </p>
                                <div class="flex space-x-6 mt-4 md:mt-0">
                                    <a href="<?php echo BASE_URL; ?>/privacy" class="text-gray-300 hover:text-nhd-green text-sm transition-colors">Privacy Policy</a>
                                    <a href="<?php echo BASE_URL; ?>/terms" class="text-gray-300 hover:text-nhd-green text-sm transition-colors">Terms of Service</a>
                                    <a href="<?php echo BASE_URL; ?>/accessibility" class="text-gray-300 hover:text-nhd-green text-sm transition-colors">Accessibility</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            <script>
                function handleBookAppointment() {
                    <?php if (isset($_SESSION["user_id"])): ?>
                        const userType = '<?php echo $_SESSION["user_type"] ??
                            ""; ?>';
                        const staffType = '<?php echo $_SESSION["staff_type"] ??
                            ""; ?>';
                        
                        if (userType === 'Patient') {
                            // Redirect to patient book appointment page
                            window.location.href = '<?php echo BASE_URL; ?>/patient/book-appointment';
                        } else if (userType === 'ClinicStaff') {
                            // Check staff type and redirect accordingly
                            if (staffType === 'Doctor') {
                                window.location.href = '<?php echo BASE_URL; ?>/doctor/dashboard';
                            } else if (staffType === 'DentalAssistant') {
                                window.location.href = '<?php echo BASE_URL; ?>/dentalassistant/dashboard';
                            } else {
                                // Default to doctor dashboard if staff type is unclear
                                window.location.href = '<?php echo BASE_URL; ?>/doctor/dashboard';
                            }
                        } else {
                            // Unknown user type, redirect to login
                            window.location.href = '<?php echo BASE_URL; ?>/login';
                            setTimeout(() => {
                                if (typeof openLoginModal === 'function') {
                                    openLoginModal();
                                }
                            }, 100);
                        }
                    <?php else: ?>
                        openLoginModal();
                    <?php endif; ?>
                }
            </script>
<?php endif; ?>
