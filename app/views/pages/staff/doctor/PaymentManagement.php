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
    <div class="glass-card rounded-2xl border-gray-200 border-1 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-nhd-blue font-family-bodoni">Appointments & Payments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full payment-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Table rows will be dynamically generated -->
                </tbody>
            </table>
        </div>
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
    </div>
</div>

<!-- Payment Management Modal -->
<div id="paymentModal" class="payment-modal fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="payment-modal-content relative top-20 mx-auto p-5 max-w-4xl shadow-lg rounded-2xl bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h3 class="text-2xl font-semibold text-nhd-brown font-family-sans" id="modalTitle">Payment Management</h3>
            <button id="closeModal" class="glass-card bg-nhd-blue/80 text-white text-sm transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="py-6">
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

            <!-- Payment Status and Notes -->
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <input type="text" id="paymentNotes" placeholder="Payment notes..." class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                </div>
            </div>

            <!-- Payment Items -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-nhd-blue">Payment Items</h4>
                    <button id="addItemBtn" class="glass-card bg-nhd-blue text-white px-4 py-2 rounded-2xl text-sm shadow-sm hover:bg-green-600 transition-colors">
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

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    .modal-content {
        width: 95%;
        margin: 10px auto;
    }
}
</style> 