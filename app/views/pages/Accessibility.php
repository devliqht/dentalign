
<div class="px-4 pb-8">
    <!-- Page Header -->
    <div class="pb-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Accessibility Statement</h2>
        <p class="text-gray-600">North Hill Dental is committed to ensuring digital accessibility for people with disabilities.</p>
        <p class="text-sm text-gray-500 mt-2">Last updated: <?php echo date(
            "F j, Y"
        ); ?></p>
    </div>

    <!-- Accessibility Categories -->
    <div class="glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-600 mb-4">Quick Navigation</h3>
        <div class="flex flex-wrap gap-2">
            <button class="accessibility-category-btn active glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl text-sm font-medium hover:bg-nhd-blue transition-colors shadow-sm" data-category="all">
                All Sections
            </button>
            <button class="accessibility-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="commitment">
                Our Commitment
            </button>
            <button class="accessibility-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="standards">
                Standards & Guidelines
            </button>
            <button class="accessibility-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="features">
                Accessibility Features
            </button>
            <button class="accessibility-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="assistance">
                Assistance & Feedback
            </button>
        </div>
    </div>

    <!-- Accessibility Sections -->
    <div class="space-y-4" id="accessibilityContainer">
        
        <!-- Introduction Section -->
        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="introduction">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Introduction</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <p class="mb-3">North Hill Dental is committed to ensuring that our digital services are accessible to people with disabilities. We strive to provide an inclusive experience for all users, regardless of their abilities or the technologies they use.</p>
                <p>This accessibility statement reflects our ongoing efforts to improve the usability of our website and digital platforms for everyone, including those who rely on assistive technologies.</p>
            </div>
        </div>

        <!-- Our Commitment Category -->
        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="commitment">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Our Commitment to Accessibility</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <p>At North Hill Dental, we believe that everyone deserves equal access to quality dental care and information. Our commitment extends beyond our physical facilities to include our digital presence.</p>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Our Accessibility Goals</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Ensure our website is usable by people with various disabilities</li>
                            <li>Provide multiple ways to access information and services</li>
                            <li>Maintain compatibility with assistive technologies</li>
                            <li>Continuously improve our accessibility features</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Inclusive Design Principles</h4>
                        <p>We design our digital services with accessibility in mind from the beginning, ensuring that all users can navigate, understand, and interact with our content effectively.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="commitment">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Physical Accessibility</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Our Clinic Facilities</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Wheelchair accessible entrance and parking</li>
                            <li>Accessible restrooms and treatment rooms</li>
                            <li>Clear pathways and adequate lighting</li>
                            <li>Accessible seating in waiting areas</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Assistive Services</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Staff trained to assist patients with disabilities</li>
                            <li>Flexible appointment scheduling</li>
                            <li>Communication assistance when needed</li>
                            <li>Transfer assistance for patients with mobility challenges</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Standards & Guidelines Category -->
        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="standards">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Web Accessibility Standards</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">WCAG 2.1 Guidelines</h4>
                        <p>Our website aims to conform to the Web Content Accessibility Guidelines (WCAG) 2.1 Level AA standards. These guidelines explain how to make web content more accessible to people with disabilities.</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li><strong>Perceivable:</strong> Information presented in ways users can perceive</li>
                            <li><strong>Operable:</strong> User interface components are operable</li>
                            <li><strong>Understandable:</strong> Information and UI operation is understandable</li>
                            <li><strong>Robust:</strong> Content can be interpreted by assistive technologies</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Compliance Status</h4>
                        <p>We regularly audit our website and are committed to addressing any accessibility barriers we identify. Our goal is to achieve and maintain WCAG 2.1 AA compliance.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="standards">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Testing and Evaluation</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Regular Testing Methods</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Automated accessibility testing tools</li>
                            <li>Manual testing with keyboard navigation</li>
                            <li>Screen reader compatibility testing</li>
                            <li>Color contrast and visual accessibility checks</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Ongoing Improvements</h4>
                        <p>We continuously monitor and improve our accessibility features based on user feedback, testing results, and evolving best practices in web accessibility.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accessibility Features Category -->
        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="features">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Website Accessibility Features</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Navigation and Structure</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Logical heading structure (H1, H2, H3, etc.)</li>
                            <li>Skip navigation links for screen readers</li>
                            <li>Consistent navigation throughout the site</li>
                            <li>Descriptive page titles and meta descriptions</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Visual and Text Features</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>High contrast color schemes</li>
                            <li>Resizable text up to 200% without loss of functionality</li>
                            <li>Alternative text for images and graphics</li>
                            <li>Clear, readable fonts and adequate spacing</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Interactive Elements</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Keyboard navigation for all interactive elements</li>
                            <li>Focus indicators for keyboard users</li>
                            <li>Descriptive link text and button labels</li>
                            <li>Form labels and error messages</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="features">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Assistive Technology Support</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Compatible Technologies</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Screen readers (JAWS, NVDA, VoiceOver)</li>
                            <li>Voice recognition software</li>
                            <li>Keyboard navigation tools</li>
                            <li>Browser zoom and magnification tools</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Supported Browsers</h4>
                        <p>Our website is tested and optimized for accessibility across major browsers including Chrome, Firefox, Safari, and Edge with assistive technologies enabled.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assistance & Feedback Category -->
        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="assistance">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Getting Help</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Need Assistance?</h4>
                        <p>If you encounter any accessibility barriers while using our website or need assistance with any of our digital services, please don't hesitate to contact us.</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>We can provide information in alternative formats</li>
                            <li>Our staff can assist with appointment scheduling</li>
                            <li>We can help navigate our services over the phone</li>
                            <li>We offer flexible communication options</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Multiple Contact Options</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Phone: 0927 508 6540</li>
                            <li>Email: accessibility@northhilldental.ph</li>
                            <li>In-person assistance at our clinic</li>
                            <li>Online contact form with accessibility options</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="assistance">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Feedback and Suggestions</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">We Value Your Input</h4>
                        <p>Your feedback helps us improve our accessibility features and ensure we're meeting the needs of all our users. Please share your experiences and suggestions with us.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">How to Provide Feedback</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Email us at accessibility@northhilldental.ph</li>
                            <li>Call us at 0927 508 6540</li>
                            <li>Speak with our staff during your visit</li>
                            <li>Use our online feedback form</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Response Time</h4>
                        <p>We aim to respond to all accessibility-related inquiries within 2 business days and will work with you to resolve any issues promptly.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Future Improvements -->
        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="future">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Future Improvements</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <div>
                        <h4 class="font-semibold text-white mb-2">Ongoing Enhancements</h4>
                        <p>We are continuously working to improve our accessibility features. Some planned improvements include:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Enhanced screen reader compatibility</li>
                            <li>Voice navigation features</li>
                            <li>Improved mobile accessibility</li>
                            <li>Additional language support</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-2">Regular Updates</h4>
                        <p>This accessibility statement will be updated regularly to reflect our current accessibility status and any new features or improvements we implement.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="accessibility-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="contact">
            <button class="accessibility-question w-full text-left focus:outline-none" onclick="toggleAccessibilitySection(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Contact Information</h3>
                    <svg class="accessibility-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="accessibility-answer mt-4 text-gray-600 hidden">
                <div class="space-y-3">
                    <p>For accessibility-related questions, assistance, or feedback, please contact us:</p>
                    <div class="mt-2 space-y-1">
                        <p><strong>North Hill Dental</strong></p>
                        <p><strong>Accessibility Coordinator:</strong> Dr. Jane Smith</p>
                        <p><strong>Email:</strong> <a href="mailto:accessibility@northhilldental.ph" class="text-white hover:underline">accessibility@northhilldental.ph</a></p>
                        <p><strong>Phone:</strong> <a href="tel:09275086540" class="text-white hover:underline">0927 508 6540</a></p>
                        <p><strong>Address:</strong> 123 Sitio Nasipit, Barangay Banilad, Cebu City, Cebu</p>
                    </div>
                    <div class="mt-4">
                        <h4 class="font-semibold text-white mb-2">Office Hours</h4>
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
    <div class="glass-card bg-gradient-to-r from-nhd-blue/80  to-nhd-blue rounded-2xl shadow-sm p-8 text-white mt-8">
        <div class="text-center">
            <h3 class="text-2xl text-white font-bold mb-4">Still have questions?</h3>
            <p class="text-white mb-6">Can't find what you're looking for? Our support team is here to help.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="glass-card border from-nhd-blue/80 text-white px-6 py-3 rounded-2xl font-medium hover:bg-white/20 hover:text-white transition-colors shadow-sm">
                    Contact Support
                </button>
                <button class="glass-card border from-nhd-blue/80 text-white px-6 py-3 rounded-2xl font-medium hover:bg-white/20 hover:text-white transition-colors shadow-sm">
                    Call Us: (555) 123-4567
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Accessibility Functionality
function toggleAccessibilitySection(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('.accessibility-icon');
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Category Filter
document.querySelectorAll('.accessibility-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.getAttribute('data-category');
        
        // Update active button
        document.querySelectorAll('.accessibility-category-btn').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('bg-nhd-blue/80', 'text-white');
            b.classList.add('bg-gray-100/80', 'text-gray-700');
        });
        
        this.classList.add('active');
        this.classList.add('bg-nhd-blue/80', 'text-white');
        this.classList.remove('bg-gray-100/80', 'text-gray-700');
        
        // Filter accessibility items
        document.querySelectorAll('.accessibility-item').forEach(item => {
            if (category === 'all' || item.getAttribute('data-category') === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>