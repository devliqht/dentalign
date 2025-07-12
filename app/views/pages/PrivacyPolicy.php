
<div class="px-4 pb-8">
    <!-- Page Header -->
    <div class="pb-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Privacy Policy</h2>
        <p class="text-gray-600">Learn how North Hill Dental protects and manages your personal information.</p>
        <p class="text-sm text-gray-500 mt-2">Last updated: <?php echo date('F j, Y'); ?></p>
    </div>

    <!-- Privacy Policy Categories -->
    <div class="glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-600 mb-4">Quick Navigation</h3>
        <div class="flex flex-wrap gap-2">
            <button class="privacy-category-btn active glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl text-sm font-medium hover:bg-nhd-blue transition-colors shadow-sm" data-category="all">
                All Sections
            </button>
            <button class="privacy-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="collection">
                Information Collection
            </button>
            <button class="privacy-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="usage">
                How We Use Information
            </button>
            <button class="privacy-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="sharing">
                Information Sharing
            </button>
            <button class="privacy-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="security">
                Security & Rights
            </button>
        </div>
    </div>

    <!-- Privacy Policy Sections -->
    <div class="space-y-4" id="privacyContainer">
        
        <!-- Introduction Section -->
        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="introduction">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Introduction</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <p class="mb-3">At North Hill Dental, we are committed to protecting your privacy and maintaining the confidentiality of your personal and health information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our dental services and digital platforms.</p>
                <p>By using our services, you consent to the collection and use of your information as described in this policy. We comply with all applicable privacy laws and regulations, including HIPAA (Health Insurance Portability and Accountability Act) requirements.</p>
            </div>
        </div>

        <!-- Information Collection Category -->
        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="collection">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">What Information We Collect</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Personal Information</h4>
                        <p>We collect personal information that you provide to us, including:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Name, address, phone number, email address</li>
                            <li>Date of birth, gender, emergency contact information</li>
                            <li>Insurance information and payment details</li>
                            <li>Employment information when relevant</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Health Information</h4>
                        <p>We collect and maintain health information necessary for providing dental care:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Medical and dental history</li>
                            <li>Current medications and allergies</li>
                            <li>Treatment records and dental charts</li>
                            <li>X-rays and diagnostic images</li>
                            <li>Treatment plans and progress notes</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Digital Platform Information</h4>
                        <p>When you use our online services, we may collect:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Account login credentials</li>
                            <li>Appointment booking preferences</li>
                            <li>Communication history and preferences</li>
                            <li>Device information and IP addresses</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="collection">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How We Collect Information</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <p class="mb-3">We collect information through various methods:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Direct Collection:</strong> Information you provide during appointments, registration, or through our digital platforms</li>
                    <li><strong>Clinical Records:</strong> Information gathered during examinations, treatments, and consultations</li>
                    <li><strong>Third Parties:</strong> Information from insurance companies, referring dentists, or other healthcare providers (with your consent)</li>
                    <li><strong>Automated Collection:</strong> Technical information collected automatically when you use our digital services</li>
                </ul>
            </div>
        </div>

        <!-- Information Usage Category -->
        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="usage">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How We Use Your Information</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Primary Uses</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Providing dental care and treatment services</li>
                            <li>Maintaining accurate medical and dental records</li>
                            <li>Scheduling and managing appointments</li>
                            <li>Processing payments and insurance claims</li>
                            <li>Communicating about your care and appointments</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Secondary Uses</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Improving our services and patient experience</li>
                            <li>Sending appointment reminders and health tips</li>
                            <li>Complying with legal and regulatory requirements</li>
                            <li>Quality assurance and staff training purposes</li>
                            <li>Research and statistical analysis (anonymized data only)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="usage">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Legal Basis for Processing</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <p class="mb-3">We process your information based on:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Consent:</strong> You have given explicit consent for specific purposes</li>
                    <li><strong>Treatment:</strong> Processing is necessary for providing healthcare services</li>
                    <li><strong>Legal Obligation:</strong> We must comply with healthcare laws and regulations</li>
                    <li><strong>Legitimate Interest:</strong> Processing is necessary for our legitimate business interests while respecting your privacy</li>
                </ul>
            </div>
        </div>

        <!-- Information Sharing Category -->
        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="sharing">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">When We Share Information</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <p>We may share your information in the following circumstances:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>With Your Consent:</strong> When you explicitly authorize us to share information</li>
                        <li><strong>Healthcare Providers:</strong> Referrals to specialists, laboratories, or other healthcare professionals</li>
                        <li><strong>Insurance Companies:</strong> For processing claims and verifying coverage</li>
                        <li><strong>Legal Requirements:</strong> When required by law, court orders, or regulatory authorities</li>
                        <li><strong>Emergency Situations:</strong> To protect your health and safety or that of others</li>
                        <li><strong>Business Associates:</strong> Third-party service providers who assist in our operations (under strict confidentiality agreements)</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="sharing">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Third-Party Service Providers</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <p class="mb-3">We work with trusted third-party service providers to support our operations:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Cloud Storage Providers:</strong> For secure data storage and backup</li>
                    <li><strong>Payment Processors:</strong> For handling billing and payment transactions</li>
                    <li><strong>Communication Services:</strong> For appointment reminders and patient communications</li>
                    <li><strong>IT Support:</strong> For maintaining and securing our digital systems</li>
                </ul>
                <p class="mt-3 text-sm">All third-party providers are required to maintain strict confidentiality and security standards equivalent to our own.</p>
            </div>
        </div>

        <!-- Security & Rights Category -->
        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="security">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How We Protect Your Information</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Physical Security</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Secure storage of physical records in locked filing systems</li>
                            <li>Restricted access to treatment areas and record storage</li>
                            <li>Staff training on confidentiality and privacy protocols</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Digital Security</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Encryption of data in transit and at rest</li>
                            <li>Multi-factor authentication for system access</li>
                            <li>Regular security audits and vulnerability assessments</li>
                            <li>Automatic software updates and security patches</li>
                            <li>Secure backup systems with redundant storage</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="security">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Your Privacy Rights</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <p class="mb-3">You have the following rights regarding your personal information:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Access:</strong> Request a copy of your personal information we hold</li>
                    <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information</li>
                    <li><strong>Deletion:</strong> Request deletion of your information (subject to legal requirements)</li>
                    <li><strong>Restriction:</strong> Request limitation of how we use your information</li>
                    <li><strong>Portability:</strong> Request transfer of your information to another provider</li>
                    <li><strong>Objection:</strong> Object to certain uses of your information</li>
                    <li><strong>Withdraw Consent:</strong> Withdraw consent for specific uses at any time</li>
                </ul>
                <p class="mt-3 text-sm font-medium">To exercise these rights, please contact our Privacy Officer at <a href="mailto:privacy@northhilldental.ph" class="semibold text-white hover:underline">privacy@northhilldental.ph</a></p>
            </div>
        </div>

        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="security">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Data Retention</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <p>We retain your information for the following periods:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Active Patient Records:</strong> For the duration of our patient relationship</li>
                        <li><strong>Adult Patient Records:</strong> 10 years after last treatment</li>
                        <li><strong>Minor Patient Records:</strong> Until age 25 or 10 years after last treatment, whichever is longer</li>
                        <li><strong>Digital Account Data:</strong> 3 years after account closure</li>
                        <li><strong>Billing Records:</strong> 7 years as required by law</li>
                    </ul>
                    <p class="mt-3 text-sm">Records may be retained longer when required by law or for legitimate business purposes.</p>
                </div>
            </div>
        </div>

        <!-- Contact and Updates Section -->
        <div class="privacy-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="contact">
            <button class="privacy-question w-full text-left focus:outline-none" onclick="togglePrivacySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Policy Updates and Contact Information</h3>
                    <svg class="privacy-icon w-5 h-5 semibold text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="privacy-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Policy Updates</h4>
                        <p>We may update this Privacy Policy from time to time. When we make changes, we will:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Update the "Last Updated" date at the top of this policy</li>
                            <li>Notify you of significant changes via email or through our platform</li>
                            <li>Provide notice of changes during your next appointment</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Contact Us</h4>
                        <p>If you have questions about this Privacy Policy or our privacy practices, please contact us:</p>
                        <div class="mt-2 space-y-1">
                            <p><strong>Privacy Officer:</strong> Dr. Jane Smith</p>
                            <p><strong>Email:</strong> <a href="mailto:privacy@northhilldental.ph" class="semibold text-white hover:underline">privacy@northhilldental.ph</a></p>
                            <p><strong>Phone:</strong> <a href="tel:09275086540" class="semibold text-white hover:underline">0927 508 6540</a></p>
                            <p><strong>Address:</strong> 123 Sitio Nasipit, Barangay Banilad, Cebu City, Cebu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="glass-card bg-gradient-to-r from-nhd-blue/80 to-nhd-blue rounded-2xl shadow-sm p-8 text-white mt-8">
        <div class="text-center">
            <h3 class="text-2xl text-gray-600 font-bold mb-4">Questions About Your Privacy?</h3>
            <p class="semibold text-gray-600 mb-6">Our Privacy Officer is here to help you understand how we protect your information.</p>
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
// Privacy Policy Functionality
function togglePrivacySection(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('.privacy-icon');
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Category Filter
document.querySelectorAll('.privacy-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.getAttribute('data-category');
        
        // Update active button
        document.querySelectorAll('.privacy-category-btn').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('bg-nhd-blue/80', 'text-white');
            b.classList.add('bg-gray-100/80', 'text-gray-700');
        });
        
        this.classList.add('active');
        this.classList.add('bg-nhd-blue/80', 'text-white');
        this.classList.remove('bg-gray-100/80', 'text-gray-700');
        
        // Filter privacy items
        document.querySelectorAll('.privacy-item').forEach(item => {
            if (category === 'all' || item.getAttribute('data-category') === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>