<div class="pb-8">
    <div class="px-4 mb-6">
        <div class="flex items-center mb-4">
            <div>
                <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Payment History</h2>
                <p class="text-gray-600">View your payment records and invoice details</p>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="px-4 mb-6">
        <div class="flex space-x-2">
            <button onclick="showPaymentSection('all')" id="all-payments-btn" 
                    class="glass-card bg-nhd-blue/80 px-4 py-2 rounded-2xl text-white transition-colors">
                All Payments
            </button>
            <button onclick="showPaymentSection('pending')" id="pending-payments-btn" 
                    class="glass-card bg-gray-200/80 px-4 py-2 rounded-2xl text-gray-700 transition-colors hover:bg-gray-300/80">
                Pending
            </button>
            <button onclick="showPaymentSection('paid')" id="paid-payments-btn" 
                    class="glass-card bg-gray-200/80 px-4 py-2 rounded-2xl text-gray-700 transition-colors hover:bg-gray-300/80">
                Paid
            </button>
        </div>
    </div>

    <div class="px-4 space-y-6">
        <?php if (!empty($payments)): ?>
            <?php
            // Separate payments by status
            $pendingPayments = array_filter($payments, function ($p) {
                return strtolower($p["Status"]) === "pending";
            });
            $paidPayments = array_filter($payments, function ($p) {
                return strtolower($p["Status"]) === "paid";
            });
            ?>

            <!-- Pending Payments Section -->
            <div id="pending-section" class="payment-section">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-semibold text-nhd-brown font-family-bodoni">
                        Pending Payments (<?php echo count(
                            $pendingPayments
                        ); ?>)
                    </h3>
                    <button onclick="toggleSection('pending')" id="pending-toggle" 
                            class="glass-card bg-yellow-100/60 text-yellow-800 px-3 py-1 rounded-full text-sm hover:bg-yellow-200/60 transition-colors">
                        Collapse
                    </button>
                </div>
                
                <div id="pending-content" class="space-y-4">
                    <?php if (!empty($pendingPayments)): ?>
                        <?php foreach ($pendingPayments as $payment): ?>
                            <?php include "app/views/components/PaymentCardTemplate.php"; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="glass-card bg-yellow-50/50 rounded-2xl p-6 text-center">
                            <svg class="w-12 h-12 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-yellow-700">No pending payments</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Paid Payments Section -->
            <div id="paid-section" class="payment-section">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-semibold text-nhd-brown font-family-bodoni">
                        Paid Payments (<?php echo count($paidPayments); ?>)
                    </h3>
                    <button onclick="toggleSection('paid')" id="paid-toggle" 
                            class="glass-card bg-green-100/60 text-green-800 px-3 py-1 rounded-full text-sm hover:bg-green-200/60 transition-colors">
                        Collapse
                    </button>
                </div>
                
                <div id="paid-content" class="space-y-4">
                    <?php if (!empty($paidPayments)): ?>
                        <?php foreach ($paidPayments as $payment): ?>
                            <?php include "app/views/components/PaymentCardTemplate.php"; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="glass-card bg-green-50/50 rounded-2xl p-6 text-center">
                            <svg class="w-12 h-12 text-green-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-700">No paid payments yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- All Payments Section -->
            <div id="all-section" class="payment-section" style="display: none;">
                <div class="space-y-4">
                    <?php foreach ($payments as $payment): ?>
                        <?php include "app/views/components/PaymentCardTemplate.php"; ?>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php else: ?>
            <div class="glass-card rounded-2xl shadow-md p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Payment Records</h3>
                <p class="text-gray-500 mb-6">You don't have any payment records yet.</p>
                <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                   class="inline-block px-6 py-3 glass-card bg-nhd-blue/85 text-white rounded-2xl hover:bg-nhd-blue transition-colors font-medium">
                    Book an Appointment
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php // Payment Card Template - Define it inline since PHP includes work differently

function renderPaymentCard($payment)
{
    // This will be defined in the template file
} ?>

<!-- Payment Details Modal -->
<div id="paymentDetailsModal" class="fixed inset-0 bg-black/30 backdrop-blur-[1px] hidden items-center justify-center z-50 p-4">
    <div class="glass-card bg-nhd-pale/90 backdrop-blur-sm rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-semibold text-nhd-brown font-family-bodoni">Payment Details</h3>
            <button type="button" onclick="closePaymentModal()" 
                    class="glass-card bg-gray-100/80 hover:bg-gray-200/80 rounded-full p-2 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div id="paymentDetailsContent">
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-nhd-blue mx-auto"></div>
                <p class="text-gray-600 mt-4">Loading payment details...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Payment section management
function showPaymentSection(section) {
    // Hide all sections
    document.querySelectorAll('.payment-section').forEach(s => s.style.display = 'none');
    
    // Show selected section
    if (section === 'all') {
        document.getElementById('all-section').style.display = 'block';
    } else {
        document.getElementById(section + '-section').style.display = 'block';
    }
    
    // Update button states
    document.querySelectorAll('[id$="-payments-btn"]').forEach(btn => {
        btn.className = 'glass-card bg-gray-200/80 px-4 py-2 rounded-2xl text-gray-700 transition-colors hover:bg-gray-300/80';
    });
    
    document.getElementById(section + '-payments-btn').className = 'glass-card bg-nhd-blue/80 px-4 py-2 rounded-2xl text-white transition-colors';
}

// Toggle section collapse
function toggleSection(section) {
    const content = document.getElementById(section + '-content');
    const toggle = document.getElementById(section + '-toggle');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        toggle.textContent = 'Collapse';
    } else {
        content.style.display = 'none';
        toggle.textContent = 'Expand';
    }
}

// Payment details modal functions
function viewPaymentDetails(paymentId) {
    const modal = document.getElementById('paymentDetailsModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch(`<?php echo BASE_URL; ?>/patient/get-payment-details?payment_id=${paymentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('paymentDetailsContent').innerHTML = data.html;
            } else {
                document.getElementById('paymentDetailsContent').innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-red-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600">${data.message || 'Failed to load payment details'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('paymentDetailsContent').innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-600 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600">Network error. Please try again.</p>
                </div>
            `;
        });
}

function closePaymentModal() {
    const modal = document.getElementById('paymentDetailsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function printInvoice(paymentId) {
    viewPaymentDetails(paymentId);
    setTimeout(() => {
        window.print();
    }, 1000);
}

// Toggle individual payment card expand/collapse
function togglePaymentCard(paymentId) {
    const content = document.getElementById('payment-content-' + paymentId);
    const icon = document.getElementById('expand-icon-' + paymentId);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Initialize the default view
document.addEventListener('DOMContentLoaded', function() {
    // Show pending payments by default
    showPaymentSection('pending');
});
</script>

<!-- Print Styles -->
<style>
@media print {
    .glass-card {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    button, .hover\:bg-gray-200\/80 {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    #paymentDetailsModal {
        display: none !important;
    }
}
</style>
