<?php
/**
 * FAQ Page Template
 * Accessible by all authenticated users (Patients and Clinic Staff)
 */
?>

<div class="px-4 pb-8">
    <!-- Page Header -->
    <div class="pb-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Frequently Asked Questions</h2>
        <p class="text-gray-600">Find answers to common questions about our dental services and system.</p>
    </div>

    <!-- FAQ Categories -->
    <div class="glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-xl font-semibold text-nhd-blue mb-4">Browse by Category</h3>
        <div class="flex flex-wrap gap-2">
            <button class="faq-category-btn active glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl text-sm font-medium hover:bg-nhd-blue transition-colors shadow-sm" data-category="all">
                All Questions
            </button>
            <button class="faq-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="appointments">
                Appointments
            </button>
            <button class="faq-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="payments">
                Payments
            </button>
            <button class="faq-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="services">
                Services
            </button>
            <button class="faq-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="account">
                Account
            </button>
            <button class="faq-category-btn glass-card bg-gray-100/80 text-gray-700 px-4 py-2 rounded-2xl text-sm font-medium hover:bg-gray-200/80 transition-colors shadow-sm" data-category="dentalchart">
                Dental Chart
            </button>
        </div>
    </div>

    <!-- FAQ Items -->
    <div class="space-y-4" id="faqContainer">
        
        <!-- Appointments Category -->
        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="appointments">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How do I schedule an appointment?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>You can schedule an appointment by logging into your patient dashboard and clicking on "Book Appointment". Select your preferred date, time, and type of service. You'll receive a confirmation email once your appointment is booked.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="appointments">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Can I reschedule my appointment?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>Yes, you can reschedule your appointment up to 24 hours before the scheduled time. Go to your dashboard, find your appointment, and click "Reschedule" to select a new date and time.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="appointments">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">What appointment types are available?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>We offer several appointment types including: Routine Cleaning, Checkup, Filling, Root Canal, Extraction, Orthodontics, and Emergency appointments. Each type has different duration and requirements.</p>
            </div>
        </div>

        <!-- Payments Category -->
        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="payments">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">What payment methods do you accept?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>We accept cash, credit cards (Visa, MasterCard, American Express), debit cards, and bank transfers. Payment is typically due at the time of service unless other arrangements have been made.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="payments">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">What happens if my payment is overdue?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>Overdue payments may incur additional fees according to our clinic policy. You'll receive notifications about overdue payments through your dashboard and email. Please contact our billing department to discuss payment arrangements.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="payments">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How do I view my payment history?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>You can view your payment history by going to your patient dashboard and clicking on "Payments" in the navigation menu. This will show all your past payments, pending payments, and payment details.</p>
            </div>
        </div>

        <!-- Services Category -->
        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="services">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">What dental services do you offer?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>We offer comprehensive dental care including routine cleanings, fillings, crowns, root canals, extractions, teeth whitening, orthodontics, and preventive care. Our experienced dentists provide personalized treatment plans for each patient.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="services">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How long do different procedures take?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>Procedure times vary: Routine cleaning (45-60 minutes), Checkup (30 minutes), Filling (30-60 minutes), Root Canal (60-90 minutes), Extraction (30-45 minutes), and Orthodontic consultations (60 minutes).</p>
            </div>
        </div>

        <!-- Dental Chart Category -->
        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="dentalchart">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How do I view my dental chart?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>You can view your dental chart by logging into your patient dashboard and clicking on "Dental Chart" in the navigation menu. This shows the status of all your teeth and any treatment plans.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="dentalchart">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">What do the different tooth colors mean?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>The dental chart uses colors to indicate tooth status: Green means healthy, Yellow means watch/monitoring needed, Red means treatment needed, and Gray means no data available. Your dentist updates these during appointments.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="dentalchart">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">What is a treatment plan?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>A treatment plan is a detailed roadmap created by your dentist outlining the dental procedures you need. It includes specific treatments, costs, and timelines. You can view your treatment plans in your dental chart section.</p>
            </div>
        </div>

        <!-- Account Category -->
        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="account">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How do I update my account information?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>You can update your account information by logging into your dashboard and going to the "Profile" or "Account Settings" section. Make sure to keep your contact information current so we can reach you about appointments and important updates.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="account">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">I forgot my password. How can I reset it?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>On the login page, click "Forgot Password?" and enter your email address. You'll receive a password reset link in your email. Follow the instructions in the email to create a new password.</p>
            </div>
        </div>

        <div class="faq-item glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6" data-category="account">
            <button class="faq-question w-full text-left focus:outline-none" onclick="toggleFAQ(this)">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">How do I navigate the dashboard?</h3>
                    <svg class="faq-icon w-5 h-5 text-white transform transition-transform duration-200 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>
            <div class="faq-answer mt-4 text-gray-600 hidden">
                <p>Your dashboard provides quick access to all features. Patients can book appointments, view dental charts, check payments, and see bookings. Staff members have additional tools for patient management and appointment scheduling.</p>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="glass-card bg-gradient-to-r from-nhd-blue/80 to-nhd-blue rounded-2xl shadow-sm p-8 text-white mt-8">
        <div class="text-center">
            <h3 class="text-2xl text-gray-600 font-bold mb-4">Still have questions?</h3>
            <p class="text-gray-600 mb-6">Can't find what you're looking for? Our support team is here to help.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="glass-card border border-white/80 text-gray-600 px-6 py-3 rounded-2xl font-medium hover:bg-white/20 hover:text-white transition-colors shadow-sm">
                    Contact Support
                </button>
                <button class="glass-card border border-white/80 text-gray-600 px-6 py-3 rounded-2xl font-medium hover:bg-white/20 hover:text-white transition-colors shadow-sm">
                    Call Us: (555) 123-4567
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// FAQ Functionality
function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('.faq-icon');
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Category Filter
document.querySelectorAll('.faq-category-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.getAttribute('data-category');
        
        // Update active button
        document.querySelectorAll('.faq-category-btn').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('bg-nhd-blue/80', 'text-white');
            b.classList.add('bg-gray-100/80', 'text-gray-700');
        });
        
        this.classList.add('active');
        this.classList.add('bg-nhd-blue/80', 'text-white');
        this.classList.remove('bg-gray-100/80', 'text-gray-700');
        
        // Filter FAQ items
        document.querySelectorAll('.faq-item').forEach(item => {
            if (category === 'all' || item.getAttribute('data-category') === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Search Functionality
document.getElementById('faqSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    
    document.querySelectorAll('.faq-item').forEach(item => {
        const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>