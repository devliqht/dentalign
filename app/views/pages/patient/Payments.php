<?php
// Include the SortableAppointmentTable component
require_once __DIR__ . '/../../components/SortableAppointmentTable.php';

function renderPaymentTable($payments, $user)
{
    ?>
    <div class="glass-card rounded-2xl border-gray-200 border-1 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-nhd-blue font-family-sans">Payment Records</h3>
        </div>
        
        <div id="table-container" class="table-container">
            <?php if (!empty($payments)): ?>
                <!-- Mobile View -->
                <div class="block lg:hidden" id="mobile-view">
                    <div id="mobile-table-body">
                        <?php foreach ($payments as $index => $payment): ?>
                            <div class="p-4 border-b border-gray-200/30 hover:bg-white/40 transition-colors rounded-2xl glass-card mb-3 table-row" 
                                 data-row-index="<?php echo $index; ?>"
                                 data-status="<?php echo strtolower($payment["Status"]); ?>"
                                 data-doctor="<?php echo htmlspecialchars($payment["DoctorName"]); ?>"
                                 data-payment-method="<?php echo htmlspecialchars($payment["PaymentMethod"] ?? 'Cash'); ?>"
                                 data-deadline-date="<?php echo $payment["DeadlineDate"] ?? ''; ?>">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded text-xs font-medium">
                                        Payment #<?php echo str_pad($payment["PaymentID"] ?? $payment["AppointmentID"], 6, "0", STR_PAD_LEFT); ?>
                                        &nbsp;|&nbsp;
                                        Appt #<?php echo str_pad($payment["AppointmentID"], 6, "0", STR_PAD_LEFT); ?>
                                    </div>
                                    <span class="<?php echo getPaymentStatusClass($payment["Status"]); ?> px-2 py-1 rounded-full text-xs">
                                        <?php echo $payment["Status"]; ?>
                                    </span>
                                </div>
                                
                                <h4 class="font-semibold text-gray-900 mb-1">
                                    Dr. <?php echo htmlspecialchars($payment["DoctorName"]); ?>
                                </h4>
                                
                                <div class="text-sm text-gray-600 mb-2">
                                    <?php echo htmlspecialchars($payment["Specialization"]); ?>
                                </div>
                                
                                <div class="flex justify-between items-center mb-2">
                                    <div class="text-sm">
                                        <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                            <?php echo htmlspecialchars($payment["PaymentMethod"] ?? 'Cash'); ?>
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <?php if (!empty($payment["DeadlineDate"])): ?>
                                            Due: <?php echo date("M j, Y", strtotime($payment["DeadlineDate"])); ?>
                                        <?php else: ?>
                                            <span class="text-gray-400">No deadline</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if (isset($payment["total_amount"]) && $payment["total_amount"] > 0): ?>
                                    <div class="text-lg font-semibold text-nhd-brown mb-2">
                                        ₱<?php echo number_format($payment["total_amount"], 2); ?>
                                        <?php if (isset($payment["is_overdue"]) && $payment["is_overdue"] && $payment["overdue_amount"] > 0): ?>
                                            <div class="text-xs text-red-600 mt-1">
                                                (Original: ₱<?php echo number_format($payment["original_amount"], 2); ?> + ₱<?php echo number_format($payment["overdue_amount"], 2); ?> overdue fee)
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex space-x-1">
                                    <?php echo renderPaymentMobileActions($payment, $user); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Desktop View -->
                <div class="hidden lg:block overflow-x-auto px-2">
                    <table class="w-full sortable-appointment-table" data-section="payments">
                        <thead>
                            <tr class="border-b border-gray-300 bg-gray-50/50">
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PaymentID">
                                    Payment ID 
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentID">
                                    Appointment ID 
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorName">
                                    Doctor 
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="Status">
                                    Status 
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DeadlineDate">
                                    Deadline 
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PaymentMethod">
                                    Payment Method 
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="Amount">
                                    Amount 
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/30" id="table-body-payments">
                            <?php foreach ($payments as $index => $payment): ?>
                                <tr class="hover:bg-nhd-blue/10 transition-colors duration-200 border-b-1 border-gray-200 table-row" 
                                    data-row-index="<?php echo $index; ?>"
                                    data-status="<?php echo strtolower($payment["Status"]); ?>"
                                    data-doctor="<?php echo htmlspecialchars($payment["DoctorName"]); ?>"
                                    data-payment-method="<?php echo htmlspecialchars($payment["PaymentMethod"] ?? 'Cash'); ?>"
                                    data-deadline-date="<?php echo $payment["DeadlineDate"] ?? ''; ?>">
                                    <td class="py-2 px-3 font-mono text-sm text-gray-700 font-bold">
                                        #<?php echo str_pad($payment["PaymentID"] ?? $payment["AppointmentID"], 6, "0", STR_PAD_LEFT); ?>
                                    </td>
                                    <td class="py-2 px-3 font-mono text-sm text-gray-700">
                                        #<?php echo str_pad($payment["AppointmentID"], 6, "0", STR_PAD_LEFT); ?>
                                    </td>
                                    <td class="py-2 px-3">
                                        <div class="font-medium text-gray-900 text-sm">
                                            Dr. <?php echo htmlspecialchars($payment["DoctorName"]); ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo htmlspecialchars($payment["Specialization"]); ?>
                                        </div>
                                    </td>
                                    <td class="py-2 px-3">
                                        <span class="<?php echo getPaymentStatusClass($payment["Status"]); ?> px-2 py-1 rounded-full text-xs font-medium">
                                            <?php echo $payment["Status"]; ?>
                                        </span>
                                    </td>
                                    <td class="py-2 px-3">
                                        <?php if (!empty($payment["DeadlineDate"])): ?>
                                            <div class="text-sm text-gray-900">
                                                <?php echo date("M j, Y", strtotime($payment["DeadlineDate"])); ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <?php
                                                $deadline = strtotime($payment["DeadlineDate"]);
                                            $today = strtotime(date("Y-m-d"));
                                            $daysLeft = ($deadline - $today) / (60 * 60 * 24);

                                            if ($daysLeft < 0) {
                                                echo '<span class="text-red-600">Overdue by ' . abs(round($daysLeft)) . ' days</span>';
                                            } elseif ($daysLeft == 0) {
                                                echo '<span class="text-orange-600">Due today</span>';
                                            } elseif ($daysLeft <= 7) {
                                                echo '<span class="text-orange-600">Due in ' . round($daysLeft) . ' days</span>';
                                            } else {
                                                echo round($daysLeft) . ' days left';
                                            }
                                ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-sm italic">No deadline</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-2 px-3">
                                        <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                            <?php echo htmlspecialchars($payment["PaymentMethod"] ?? 'Cash'); ?>
                                        </span>
                                    </td>
                                    <td class="py-2 px-3">
                                        <?php if (isset($payment["total_amount"]) && $payment["total_amount"] > 0): ?>
                                            <div class="text-sm font-semibold text-nhd-brown">
                                                ₱<?php echo number_format($payment["total_amount"], 2); ?>
                                            </div>
                                            <?php if (isset($payment["is_overdue"]) && $payment["is_overdue"] && $payment["overdue_amount"] > 0): ?>
                                                <div class="text-xs text-red-600 mt-1">
                                                    Original: ₱<?php echo number_format($payment["original_amount"], 2); ?>
                                                </div>
                                                <div class="text-xs text-red-600">
                                                    + ₱<?php echo number_format($payment["overdue_amount"], 2); ?> overdue fee
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-sm italic">Not set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-2 px-3">
                                        <?php echo renderPaymentDesktopActions($payment, $user); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls Bottom -->
                <?php echo renderPaginationControls('payments', 'bottom'); ?>
            <?php else: ?>
                <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4 shadow-none border-1 border-gray-200">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">No payment records found</p>
                    <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                       class="inline-flex items-center px-4 py-2 glass-card bg-nhd-blue/80 text-white rounded-2xl hover:bg-nhd-blue transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Book Your First Appointment
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php
}

function renderPaymentMobileActions($payment, $user)
{
    ob_start();
    ?>
    <button onclick="viewPaymentDetails(<?php echo $payment['PaymentID'] ?? $payment['AppointmentID']; ?>)" 
            class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
        Details
    </button>
    <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $payment["AppointmentID"]; ?>')" 
            class="bg-gray-500/80 text-white px-2 py-1 rounded text-xs hover:bg-gray-600 transition-colors min-w-[120px] truncate">
        View Appointment
    </button>
    <?php
    return ob_get_clean();
}

function renderPaymentDesktopActions($payment, $user)
{
    ob_start();
    ?>
    <div class="flex space-x-1">
        <button onclick="viewPaymentDetails(<?php echo $payment['PaymentID'] ?? $payment['AppointmentID']; ?>)" 
                class="bg-nhd-blue/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-nhd-blue transition-colors">
            Details
        </button>
        <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $payment["AppointmentID"]; ?>')" 
            class="bg-gray-500/80 text-white px-2 py-1 rounded-full text-xs hover:bg-gray-600 transition-colors">
            View..
        </button>
    </div>
    <?php
    return ob_get_clean();
}


?>

<div class="pb-8">
    <div class="px-4 mb-6">
        <div class="flex items-center mb-4">
            <div>
                <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Payment History</h2>
                <p class="text-gray-600">View your payment records and invoice details</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="px-4 mb-6">
        <div class="filter-section rounded-2xl pt-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Payment Status</label>
                    <select id="filterStatus" class="w-full h-fit p-3 border text-sm border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                        <option value="">All Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="overdue">Overdue</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deadline Range</label>
                    <select id="filterDateRange" class="w-full h-fit p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                        <option value="">All Deadlines</option>
                        <option value="today">Due Today</option>
                        <option value="week">Due This Week</option>
                        <option value="month">Due This Month</option>
                        <option value="overdue">Overdue</option>
                        <option value="no-deadline">No Deadline</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Doctor</label>
                    <select id="filterDoctor" class="w-full h-fit p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                        <option value="">All Doctors</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Payment Method</label>
                    <select id="filterPaymentMethod" class="w-full h-fit p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                        <option value="">All Methods</option>
                        <option value="Cash">Cash</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Check">Check</option>
                        <option value="Digital Wallet">Digital Wallet</option>
                        <option value="Insurance">Insurance</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm text-gray-600">
                    <span id="resultsCount">0</span> payments found
                </div>
                <button onclick="clearAllFilters()" class="text-sm glass-card bg-nhd-blue/80 hover:bg-nhd-blue text-white transition-colors">
                    Clear All Filters
                </button>
            </div>
        </div>
    </div>

    <!-- No Data Message -->
    <div class="px-4">
        <div id="noDataMessage" class="text-center py-8 text-gray-500 hidden">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
            <p>No payments match your current filters</p>
            <button onclick="clearAllFilters()" class="mt-4 inline-flex items-center px-4 py-2 glass-card bg-nhd-blue/80 text-white rounded-2xl hover:bg-nhd-blue transition-colors">
                Clear Filters
            </button>
        </div>
        
        <!-- Payment Table -->
        <?php if (!empty($payments)): ?>
            <?php renderPaymentTable($payments, $user); ?>
        <?php else: ?>
            <div class="glass-card rounded-2xl shadow-md p-8 text-center shadow-none border-1 border-gray-200">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Appointments Yet</h3>
                <p class="text-gray-500 mb-6">You haven't booked any appointments yet. Start by booking your first appointment.</p>
                <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                   class="inline-block px-6 py-3 glass-card bg-nhd-blue/85 text-white rounded-2xl hover:bg-nhd-blue transition-colors font-medium">
                    Book Your First Appointment
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>



<!-- Payment Details Modal -->
<div id="paymentDetailsModal" class="fixed inset-0 bg-black/30 backdrop-blur-[1px] hidden items-center justify-center z-50 p-4">
    <div class="glass-card bg-white/90 backdrop-blur-sm rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
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

<script src="<?php echo BASE_URL; ?>/app/views/scripts/SortableTable.js"></script>
<script>
class PaymentFilter {
    constructor() {
        this.allPayments = [];
        this.filteredPayments = [];
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadPaymentData();
        this.populateFilterOptions();
    }

    bindEvents() {
        document.getElementById('filterStatus').addEventListener('change', () => this.applyFilters());
        document.getElementById('filterDateRange').addEventListener('change', () => this.applyFilters());
        document.getElementById('filterDoctor').addEventListener('change', () => this.applyFilters());
        document.getElementById('filterPaymentMethod').addEventListener('change', () => this.applyFilters());
    }

    loadPaymentData() {
        // Extract payment data from table rows
        const rows = document.querySelectorAll('.table-row');
        this.allPayments = Array.from(rows).map(row => ({
            element: row,
            status: row.getAttribute('data-status'),
            doctor: row.getAttribute('data-doctor'),
            paymentMethod: row.getAttribute('data-payment-method'),
            deadlineDate: row.getAttribute('data-deadline-date')
        }));
        this.filteredPayments = [...this.allPayments];
        this.updateResultsCount();
    }

    populateFilterOptions() {
        const doctorSelect = document.getElementById('filterDoctor');
        const doctors = [...new Set(this.allPayments.map(payment => payment.doctor).filter(Boolean))];
        
        doctors.forEach(doctor => {
            const option = document.createElement('option');
            option.value = doctor;
            option.textContent = `Dr. ${doctor}`;
            doctorSelect.appendChild(option);
        });
    }

    applyFilters() {
        const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
        const dateRangeFilter = document.getElementById('filterDateRange').value;
        const doctorFilter = document.getElementById('filterDoctor').value;
        const paymentMethodFilter = document.getElementById('filterPaymentMethod').value;

        this.filteredPayments = this.allPayments.filter(payment => {
            // Status filter
            if (statusFilter && payment.status !== statusFilter) {
                return false;
            }

            // Doctor filter
            if (doctorFilter && payment.doctor !== doctorFilter) {
                return false;
            }

            // Payment method filter
            if (paymentMethodFilter && payment.paymentMethod !== paymentMethodFilter) {
                return false;
            }

            // Date range filter
            if (dateRangeFilter) {
                const deadlineDate = payment.deadlineDate ? new Date(payment.deadlineDate) : null;
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                switch (dateRangeFilter) {
                    case 'today':
                        if (!deadlineDate || deadlineDate.toDateString() !== today.toDateString()) {
                            return false;
                        }
                        break;
                    case 'week':
                        if (!deadlineDate) return false;
                        const weekFromNow = new Date(today);
                        weekFromNow.setDate(today.getDate() + 7);
                        if (deadlineDate < today || deadlineDate > weekFromNow) {
                            return false;
                        }
                        break;
                    case 'month':
                        if (!deadlineDate) return false;
                        const monthFromNow = new Date(today);
                        monthFromNow.setMonth(today.getMonth() + 1);
                        if (deadlineDate < today || deadlineDate > monthFromNow) {
                            return false;
                        }
                        break;
                    case 'overdue':
                        if (!deadlineDate || deadlineDate >= today) {
                            return false;
                        }
                        break;
                    case 'no-deadline':
                        if (deadlineDate) {
                            return false;
                        }
                        break;
                }
            }

            return true;
        });

        this.updateDisplay();
        this.updateResultsCount();
    }

    updateDisplay() {
        const noDataMessage = document.getElementById('noDataMessage');
        const tableContainer = document.getElementById('table-container');

        // Show/hide all payment rows
        this.allPayments.forEach(payment => {
            payment.element.style.display = 'none';
        });

        // Show filtered payment rows
        this.filteredPayments.forEach(payment => {
            payment.element.style.display = '';
        });

        // Show/hide no data message
        if (this.filteredPayments.length === 0) {
            noDataMessage.classList.remove('hidden');
            tableContainer.style.display = 'none';
        } else {
            noDataMessage.classList.add('hidden');
            tableContainer.style.display = 'block';
        }
    }

    updateResultsCount() {
        const resultsCount = document.getElementById('resultsCount');
        if (resultsCount) {
            resultsCount.textContent = this.filteredPayments.length;
        }
    }
}

function clearAllFilters() {
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterDateRange').value = '';
    document.getElementById('filterDoctor').value = '';
    document.getElementById('filterPaymentMethod').value = '';
    
    if (window.paymentFilter) {
        window.paymentFilter.applyFilters();
    }
}

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

function navigateToAppointment(url) {
    window.location.href = url;
}

function printInvoice(paymentId) {
    viewPaymentDetails(paymentId);
    setTimeout(() => {
        window.print();
    }, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize payment filter
    window.paymentFilter = new PaymentFilter();
    
    // Initialize sortable table manager
    const sortableManager = new SortableTableManager();
    sortableManager.init();
    
    // Initialize pagination manager - only if it exists
    if (typeof PaginationManager !== 'undefined') {
        const paginationManager = new PaginationManager();
        paginationManager.init();
    }
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
    
    .filter-section {
        display: none !important;
    }
}

.sortable-header {
    user-select: none;
    position: relative;
}

.sortable-header:hover {
    background-color: rgba(229, 231, 235, 0.6) !important;
}

.sort-indicator {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 16px;
    height: 16px;
    transition: all 0.2s ease;
}

.sort-icon-default {
    opacity: 0.5;
    transition: opacity 0.2s ease;
}

.sortable-header:hover .sort-icon-default {
    opacity: 0.8;
}

.sort-icon-active {
    font-size: 12px;
    font-weight: bold;
    color: #374151;
}

.sort-icon-active.asc::before {
    content: '▲';
    color: #059669;
}

.sort-icon-active.desc::before {
    content: '▼';
    color: #DC2626;
}

.sortable-header.sorting .sort-icon-default {
    display: none;
}

.sortable-header.sorting .sort-icon-active {
    display: inline-block;
}

.glass-card {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}
</style>
