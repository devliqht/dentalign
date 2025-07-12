

<div class="px-4 pb-8">
    <!-- Page Header -->
    <div class="pb-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Terms of Service</h2>
        <p class="text-gray-600">Please read these terms carefully before using North Hill Dental's services.</p>
        <p class="text-sm text-gray-500 mt-2">Last updated: <?php echo date('F j, Y'); ?></p>
    </div>

    <!-- Terms of Service Categories -->
    <div class="glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-xl font-semibold text-white mb-4">Quick Navigation</h3>
        <div class="flex flex-wrap gap-2">
            <button class="terms-category-btn active glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl text-sm font-medium hover:bg-nhd-blue transition-colors shadow-sm" data-category="all">
                All Sections
            </button>
            <button class="terms-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="acceptance">
                Acceptance & Agreement
            </button>
            <button class="terms-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="services">
                Services & Appointments
            </button>
            <button class="terms-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="payment">
                Payment & Billing
            </button>
            <button class="terms-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="liability">
                Liability & Conduct
            </button>
        </div>
    </div>

    <!-- Terms of Service Sections -->
    <div class="space-y-4" id="termsContainer">
        
        <!-- Introduction Section -->
        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="introduction">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Introduction</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <p class="mb-3">Welcome to North Hill Dental. These Terms of Service ("Terms") govern your use of our dental services, digital platforms, and any related services provided by North Hill Dental ("we," "us," or "our").</p>
                <p>By accessing our services, scheduling appointments, or using our digital platforms, you agree to be bound by these Terms. If you do not agree to these Terms, please do not use our services.</p>
            </div>
        </div>

        <!-- Acceptance & Agreement Category -->
        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="acceptance">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Acceptance of Terms</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <p>By using our services, you acknowledge that you have read, understood, and agree to be bound by these Terms and all applicable laws and regulations.</p>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Agreement Conditions</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>You are at least 18 years old or have parental/guardian consent</li>
                            <li>You have the legal capacity to enter into this agreement</li>
                            <li>You will comply with all applicable laws and regulations</li>
                            <li>You will provide accurate and complete information</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Modifications</h4>
                        <p>We reserve the right to modify these Terms at any time. Changes will be effective when posted on our website or communicated to you. Continued use of our services after changes constitutes acceptance of the new Terms.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="acceptance">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Eligibility and Registration</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Account Registration</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>You must provide accurate, current, and complete information during registration</li>
                            <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                            <li>You must notify us immediately of any unauthorized access to your account</li>
                            <li>You are responsible for all activities that occur under your account</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Prohibited Users</h4>
                        <p>You may not use our services if you:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Have been previously banned or suspended from our services</li>
                            <li>Are not in good standing with our clinic</li>
                            <li>Are using our services for illegal or unauthorized purposes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services & Appointments Category -->
        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="services">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Dental Services</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Services Provided</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>General dentistry and preventive care</li>
                            <li>Cosmetic dentistry procedures</li>
                            <li>Oral surgery and specialized treatments</li>
                            <li>Emergency dental care</li>
                            <li>Digital appointment scheduling and management</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Service Limitations</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Services are provided subject to availability</li>
                            <li>We reserve the right to refuse service in certain circumstances</li>
                            <li>Treatment recommendations are based on professional judgment</li>
                            <li>Results may vary based on individual circumstances</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="services">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Appointments and Scheduling</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Appointment Booking</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Appointments must be scheduled in advance through our system</li>
                            <li>You will receive confirmation of your appointment via email or SMS</li>
                            <li>Please arrive 15 minutes early for your appointment</li>
                            <li>Walk-in appointments are subject to availability</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Cancellation Policy</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Cancellations must be made at least 24 hours in advance</li>
                            <li>Late cancellations may incur a fee</li>
                            <li>No-show appointments may be charged a fee</li>
                            <li>Repeated no-shows may result in appointment booking restrictions</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Rescheduling</h4>
                        <p>You may reschedule appointments subject to availability. Multiple rescheduling requests may be subject to restrictions.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment & Billing Category -->
        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="payment">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Payment Terms</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Payment Methods</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Cash, credit cards, and debit cards are accepted</li>
                            <li>Insurance claims will be processed as applicable</li>
                            <li>Payment plans may be available for qualifying treatments</li>
                            <li>All payments are due at the time of service unless otherwise arranged</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Billing and Invoicing</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Invoices will be provided for all services rendered</li>
                            <li>Insurance co-pays and deductibles are due at time of service</li>
                            <li>Outstanding balances are subject to collection procedures</li>
                            <li>Late fees may apply to overdue accounts</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="payment">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Insurance and Coverage</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <p>We accept most major insurance plans and will assist with claims processing.</p>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Insurance Responsibilities</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>You are responsible for understanding your insurance benefits</li>
                            <li>We will verify benefits as a courtesy, but accuracy is not guaranteed</li>
                            <li>You are responsible for all charges not covered by insurance</li>
                            <li>Pre-authorizations must be obtained when required</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Claims Processing</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>We will submit claims on your behalf as a courtesy</li>
                            <li>Payment from insurance companies may take 30-90 days</li>
                            <li>Outstanding balances after insurance processing are your responsibility</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liability & Conduct Category -->
        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="liability">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Patient Responsibilities</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Medical History and Disclosure</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Provide complete and accurate medical and dental history</li>
                            <li>Disclose all medications, allergies, and medical conditions</li>
                            <li>Update us of any changes to your health status</li>
                            <li>Follow all pre and post-treatment instructions</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Conduct and Behavior</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Treat all staff and other patients with respect and courtesy</li>
                            <li>Follow all clinic policies and procedures</li>
                            <li>Maintain appropriate behavior during visits</li>
                            <li>Report any concerns or issues promptly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="liability">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Limitation of Liability</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Professional Liability</h4>
                        <p>Our services are provided in accordance with professional standards of care. We maintain appropriate professional liability insurance as required by law.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Treatment Outcomes</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>We do not guarantee specific treatment outcomes</li>
                            <li>Results may vary based on individual circumstances</li>
                            <li>Follow-up care may be necessary for optimal results</li>
                            <li>Complications, while rare, may occur with any treatment</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Digital Services</h4>
                        <p>Our digital platforms are provided "as is" without warranties. We are not liable for technical issues, data loss, or service interruptions.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="liability">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Prohibited Conduct</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <p>The following conduct is prohibited and may result in termination of services:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Abusive, threatening, or disruptive behavior toward staff or patients</li>
                        <li>Providing false or misleading information</li>
                        <li>Unauthorized use of our digital systems</li>
                        <li>Violation of any applicable laws or regulations</li>
                        <li>Interference with clinic operations</li>
                        <li>Failure to comply with treatment instructions</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Termination and Dispute Resolution -->
        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="termination">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Termination and Dispute Resolution</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Termination of Services</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Either party may terminate the patient-provider relationship with proper notice</li>
                            <li>We reserve the right to terminate services for violation of these Terms</li>
                            <li>Emergency care will be provided for a reasonable period after termination</li>
                            <li>Patient records will be transferred as requested and legally permitted</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Dispute Resolution</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>We encourage direct communication to resolve any concerns</li>
                            <li>Formal complaints should be submitted in writing</li>
                            <li>Disputes may be subject to mediation or arbitration</li>
                            <li>These Terms are governed by local laws and regulations</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="terms-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="contact">
            <button class="terms-question w-full text-left focus:outline-none" onclick="toggleTermsSection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Contact Information</h3>
                    <svg class="terms-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="terms-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <p>If you have questions about these Terms of Service, please contact us:</p>
                    <div class="mt-2 space-y-1">
                        <p><strong>North Hill Dental</strong></p>
                        <p><strong>Email:</strong> <a href="mailto:info@northhilldental.ph" class="text-white hover:underline">info@northhilldental.ph</a></p>
                        <p><strong>Phone:</strong> <a href="tel:09275086540" class="text-white hover:underline">0927 508 6540</a></p>
                        <p><strong>Address:</strong> 123 Sitio Nasipit, Barangay Banilad, Cebu City, Cebu</p>
                    </div>
                    <div class="mt-4">
                        <h4 class="font-semibold text-white mb-2">Business Hours</h4>
                        <ul class="text-sm space-y-1">
                            <li>Monday - Friday: 8:00 AM - 6:00 PM</li>
                            <li>Saturday: 9:00 AM - 4:00 PM</li>
                            <li>Sunday: Closed</li>
                            <li>Emergency: 24/7 by appointment</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="glass-card bg-gradient-to-r from-nhd-blue/80 to-nhd-blue rounded-2xl shadow-sm p-8 text-white mt-8">
        <div class="text-center">
            <h3 class="text-2xl text-gray-600 font-bold mb-4">Questions About Our Terms?</h3>
            <p class="text-gray-600  mb-6">Our team is here to clarify any questions you may have about our Terms of Service.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo BASE_URL; ?>/login#contact" class="glass-card border border-white/80 text-gray-600 px-6 py-3 rounded-2xl font-medium hover:bg-nhd-blue hover:text-white transition-colors shadow-sm">
                    Contact Support
                </a>
                <a href="<?php echo BASE_URL; ?>/login#contact" class="glass-card border border-white/80 text-gray-600 px-6 py-3 rounded-2xl font-medium hover:bg-nhd-blue hover:text-white transition-colors shadow-sm">
                    Call Us: (555) 123-4567
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Terms of Service Functionality
function toggleTermsSection(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('.terms-icon');
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Category Filter
document.querySelectorAll('.terms-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.getAttribute('data-category');
        
        // Update active button
        document.querySelectorAll('.terms-category-btn').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('bg-nhd-blue/80', 'text-white');
            b.classList.add('bg-gray-100/80', 'text-gray-700');
        });
        
        this.classList.add('active');
        this.classList.add('bg-nhd-blue/80', 'text-white');
        this.classList.remove('bg-gray-100/80', 'text-gray-700');
        
        // Filter terms items
        document.querySelectorAll('.terms-item').forEach(item => {
            if (category === 'all' || item.getAttribute('data-category') === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>