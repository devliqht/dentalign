<?php
// Include the SortableAppointmentTable component
require_once __DIR__ . '/../../../components/SortableAppointmentTable.php';

// Function to render payment management table
function renderPaymentManagementTable($appointments, $appointmentPayments, $user)
{
    ?>
    <div class="glass-card rounded-2xl border-gray-200 border-1 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-nhd-blue font-family-bodoni">Appointments & Payments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full payment-table" id="paymentManagementTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DateTime">
                            Date & Time 
                            <span class="sort-indicator ml-1">
                                <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                    <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                    <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                </svg>
                                <span class="sort-icon-active hidden"></span>
                            </span>
                        </th>
                        <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientFirstName">
                            Patient 
                            <span class="sort-indicator ml-1">
                                <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                    <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                    <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                </svg>
                                <span class="sort-icon-active hidden"></span>
                            </span>
                        </th>
                        <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorFirstName">
                            Doctor 
                            <span class="sort-indicator ml-1">
                                <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                    <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                    <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                </svg>
                                <span class="sort-icon-active hidden"></span>
                            </span>
                        </th>

                        <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="TotalAmount">
                            Amount 
                            <span class="sort-indicator ml-1">
                                <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                    <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                    <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                </svg>
                                <span class="sort-icon-active hidden"></span>
                            </span>
                        </th>
                        <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PaymentStatus">
                            Payment Status 
                            <span class="sort-indicator ml-1">
                                <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                    <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                    <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                </svg>
                                <span class="sort-icon-active hidden"></span>
                            </span>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Table rows will be dynamically generated -->
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>

<div class="px-4 pb-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
                    Payment Management
                </h1>
                <p class="text-gray-600">Manage appointments payments and billing for all patients</p>
            </div>
            <div class="flex space-x-3">
                <button id="refreshDataBtn" class="glass-card bg-nhd-blue text-white px-4 py-2 rounded-xl shadow-sm hover:bg-nhd-blue/90 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Overdue Configuration -->
    <div class="glass-card bg-blue-50/50 rounded-2xl border-blue-200 border-1 shadow-sm mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-nhd-blue">Overdue Payment Configuration</h3>
            <button id="editConfigBtn" class="glass-card bg-nhd-blue text-white px-4 py-2 rounded-xl shadow-sm hover:bg-nhd-blue/90 transition-colors">
                <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>Edit Configuration
            </button>
        </div>
        
        <div id="configDisplay" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="glass-card bg-white/60 p-4 rounded-xl shadow-none border-1 border-gray-200">
                <p class="text-sm text-gray-600">Overdue Fee Percentage</p>
                <p class="text-2xl font-bold text-nhd-blue" id="currentPercentage">5.00%</p>
            </div>
            <div class="glass-card bg-white/60 p-4 rounded-xl shadow-none border-1 border-gray-200">
                <p class="text-sm text-gray-600">Grace Period</p>
                <p class="text-2xl font-bold text-nhd-blue" id="currentGracePeriod">0 days</p>
            </div>
            <div class="glass-card bg-white/60 p-4 rounded-xl shadow-none border-1 border-gray-200">
                <p class="text-sm text-gray-600">Last Updated</p>
                <p class="text-sm font-medium text-gray-700" id="lastUpdated">Never</p>
            </div>
        </div>
        
        <div id="configForm" class="hidden mt-4 p-4 bg-white/60 rounded-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overdue Fee Percentage (%)</label>
                    <input type="number" id="overduePercentage" step="0.01" min="0" max="100" class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Grace Period (Days)</label>
                    <input type="number" id="gracePeriodDays" min="0" max="365" class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Configuration Name</label>
                <input type="text" id="configName" placeholder="Enter configuration name..." class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
            </div>
            <div class="flex space-x-3">
                <button id="saveConfigBtn" class="glass-card bg-green-500 text-white px-4 py-2 rounded-xl shadow-sm hover:bg-green-600 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>Save Configuration
                </button>
                <button id="cancelConfigBtn" class="glass-card bg-gray-500 text-white px-4 py-2 rounded-xl shadow-sm hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card stat-card p-6 rounded-2xl border-gray-200 border-1 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Paid</p>
                    <p class="text-2xl font-bold text-green-600" id="paidCount">0</p>
                </div>
            </div>
        </div>
        <div class="glass-card stat-card p-6 rounded-2xl border-gray-200 border-1 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600" id="pendingCount">0</p>
                </div>
            </div>
        </div>
        <div class="glass-card stat-card p-6 rounded-2xl border-gray-200 border-1 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Overdue</p>
                    <p class="text-2xl font-bold text-red-600" id="overdueCount">0</p>
                </div>
            </div>
        </div>
        <div class="glass-card stat-card p-6 rounded-2xl border-gray-200 border-1 shadow-sm">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-blue-600" id="totalRevenue">₱0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card filter-section p-6 rounded-2xl border-gray-200 border-1 shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Payment Status</label>
                <select id="filterStatus" class="w-full h-fit p-3 border text-sm border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                    <option value="">All Status</option>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Overdue">Overdue</option>
                    <option value="Cancelled">Cancelled</option>
                    <option value="Failed">Failed</option>
                    <option value="Refunded">Refunded</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Date Range</label>
                <select id="filterDateRange" class="w-full h-fit p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Doctor</label>
                <select id="filterDoctor" class="w-full h-fit p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                    <option value="">All Doctors</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Patient</label>
                <input type="text" id="searchPatient" placeholder="Patient name..." class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div id="appointments-table-container">
        <div id="loadingSpinner" class="text-center py-8">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-nhd-blue bg-white">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-nhd-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading appointments...
            </div>
        </div>
        <div id="noDataMessage" class="text-center py-8 text-gray-500 hidden">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                <path d="M8.293 12.293a1 1 0 011.414 0L11 13.586l1.293-1.293a1 1 0 111.414 1.414L12.414 15l1.293 1.293a1 1 0 01-1.414 1.414L11 16.414l-1.293 1.293a1 1 0 01-1.414-1.414L9.586 15l-1.293-1.293a1 1 0 010-1.414z"></path>
            </svg>
            <p>No appointments found</p>
        </div>
        <div id="appointments-table-content">
            <!-- SortableAppointmentTable will be rendered here -->
        </div>
        
        <!-- Pagination Controls -->
        <div id="pagination-controls-container" class="hidden">
            <?php echo renderPaginationControls('payment-management'); ?>
        </div>
    </div>
</div>

<!-- Payment Management Modal -->
<div id="paymentModal" class="payment-modal fixed inset-0 bg-gray-600/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="payment-modal-content w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-lg rounded-2xl bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200 p-5">
            <h3 class="text-2xl font-semibold text-nhd-brown font-family-sans" id="modalTitle">Payment Management</h3>
            <button id="closeModal" class="glass-card bg-nhd-blue/80 text-white text-sm transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-5 pt-6">
            <!-- Appointment Info -->
            <div id="appointmentInfo" class="glass-card border-gray-200 border-1 p-4 shadow-sm rounded-xl mb-6">
                <h4 class="font-semibold text-nhd-blue mb-2">Appointment Dates</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Patient:</span>
                        <span class="font-medium ml-2" id="modalPatientName">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Doctor:</span>
                        <span class="font-medium ml-2" id="modalDoctorName">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Date & Time:</span>
                        <span class="font-medium ml-2" id="modalDateTime">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Type:</span>
                        <span class="font-medium ml-2" id="modalAppointmentType">-</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status and Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <select id="paymentStatus" class="w-full h-fit text-sm p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                        <option value="Pending">Pending</option>
                        <option value="Paid">Paid</option>
                        <option value="Overdue">Overdue</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="paymentMethod" class="w-full h-fit text-sm p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                        <option value="Cash">Cash</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Check">Check</option>
                        <option value="Digital Wallet">Digital Wallet</option>
                        <option value="Insurance">Insurance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deadline Date</label>
                    <input type="date" id="deadlineDate" class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Payment</label>
                    <input type="text" id="proofOfPayment" placeholder="Receipt number, transaction ID, etc..." class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <input type="text" id="paymentNotes" placeholder="Payment notes..." class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                </div>
            </div>

            <!-- Payment Items -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-nhd-blue">Payment Items</h4>
                    <button id="addItemBtn" class="glass-card bg-white/80 border-gray-200 border-1 text-nhd-blue px-4 py-2 rounded-2xl text-sm shadow-sm hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>Add Item
                    </button>
                </div>
                
                <div id="paymentItemsContainer" class="space-y-2">
                    <!-- Payment items will be dynamically added here -->
                </div>
                
                <!-- Total -->
                <div class="glass-card p-4 rounded-2xl shadow-sm bg-nhd-blue/5 border border-nhd-blue/20 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Total Amount:</span>
                        <span class="text-2xl font-bold" id="totalAmount">₱0.00</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 justify-end">
                <button id="deletePaymentBtn" class="action-btn glass-card bg-red-500 text-white px-4 py-2 rounded-2xl shadow-sm hover:bg-red-600 transition-colors hidden">
                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>Delete Payment
                </button>
                <button id="markAsPaidBtn" class="action-btn glass-card bg-green-500 text-white px-4 py-2 rounded-2xl shadow-sm hover:bg-green-600 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>Mark as Paid
                </button>
                <button id="markAsPendingBtn" class="action-btn glass-card bg-yellow-500 text-white px-4 py-2 rounded-2xl shadow-sm hover:bg-yellow-600 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>Mark as Pending
                </button>
                <button id="savePaymentBtn" class="action-btn glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl shadow-sm hover:bg-nhd-blue/90 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"></path>
                    </svg>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.BASE_URL = '<?php echo BASE_URL; ?>';
</script>

<style>
.glass-card {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.payment-item {
    transition: all 0.3s ease;
}

.payment-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.status-badge {
    @apply px-3 py-1 rounded-full text-xs font-medium;
}

.status-paid {
    @apply bg-green-100 text-green-800;
}

.status-pending {
    @apply bg-yellow-100 text-yellow-800;
}

.status-overdue {
    @apply bg-red-100 text-red-800;
}

.status-cancelled {
    @apply bg-gray-100 text-gray-800;
}

.status-overdue {
    @apply bg-red-100 text-red-800;
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    .payment-modal-content {
        max-width: 95%;
        max-height: 85vh;
    }
}
</style>