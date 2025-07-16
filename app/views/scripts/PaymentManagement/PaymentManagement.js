// Prevent duplicate class declarations
if (typeof window.PaymentManagement === 'undefined') {
  window.PaymentManagement = class PaymentManagement {
  constructor() {
    this.appointments = [];
    this.filteredAppointments = [];
    this.currentPayment = null;
    this.currentAppointment = null;
    this.paymentItems = [];
    this.isEditMode = false;
    this.currentSection = 'all';
    this.currentSort = null;

    this.init();
  }

  init() {
    this.bindEvents();
    this.loadAppointments();
  }

  bindEvents() {
    // Basic functionality buttons
    const refreshBtn = document.getElementById("refreshDataBtn");
    if (refreshBtn) {
      refreshBtn.addEventListener("click", () => this.loadAppointments());
    }

    const closeModalBtn = document.getElementById("closeModal");
    if (closeModalBtn) {
      closeModalBtn.addEventListener("click", () => this.closeModal());
    }

    const paymentModal = document.getElementById("paymentModal");
    if (paymentModal) {
      paymentModal.addEventListener("click", (e) => {
        if (e.target.id === "paymentModal") this.closeModal();
      });
    }

    // Filter events
    const filterStatus = document.getElementById("filterStatus");
    if (filterStatus) {
      filterStatus.addEventListener("change", () => this.applyFilters());
    }

    const filterDateRange = document.getElementById("filterDateRange");
    if (filterDateRange) {
      filterDateRange.addEventListener("change", () => this.applyFilters());
    }

    const filterDoctor = document.getElementById("filterDoctor");
    if (filterDoctor) {
      filterDoctor.addEventListener("change", () => this.applyFilters());
    }

    const searchPatient = document.getElementById("searchPatient");
    if (searchPatient) {
      searchPatient.addEventListener("input", () => this.applyFilters());
    }

    // Payment modal events
    const addItemBtn = document.getElementById("addItemBtn");
    if (addItemBtn) {
      addItemBtn.addEventListener("click", () => this.addPaymentItem());
    }

    const savePaymentBtn = document.getElementById("savePaymentBtn");
    if (savePaymentBtn) {
      savePaymentBtn.addEventListener("click", () => this.savePayment());
    }

    const deletePaymentBtn = document.getElementById("deletePaymentBtn");
    if (deletePaymentBtn) {
      deletePaymentBtn.addEventListener("click", () => this.deletePayment());
    }

    const markAsPaidBtn = document.getElementById("markAsPaidBtn");
    if (markAsPaidBtn) {
      markAsPaidBtn.addEventListener("click", () => this.updatePaymentStatus("Paid"));
    }

    const markAsPendingBtn = document.getElementById("markAsPendingBtn");
    if (markAsPendingBtn) {
      markAsPendingBtn.addEventListener("click", () => this.updatePaymentStatus("Pending"));
    }

    const paymentStatus = document.getElementById("paymentStatus");
    if (paymentStatus) {
      paymentStatus.addEventListener("change", (e) => {
        this.updateQuickActionButtons(e.target.value);
      });
    }

    console.log("Events bound successfully");
  }

  async loadAppointments() {
    try {
      this.showLoading(true);

      const url = `${window.BASE_URL}/dentalassistant/get-all-appointments-payments`;
      const response = await fetch(url);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();

      if (data.success) {
        this.appointments = data.appointments || [];
        this.filteredAppointments = [...this.appointments];
        this.renderAppointmentsSections();
        this.updateStats();
        this.populateFilterOptions();
      } else {
        this.appointments = [];
        this.filteredAppointments = [];
        this.renderAppointmentsSections();
        this.updateStats();
        this.populateFilterOptions();
        this.showToast("Error loading appointments: " + (data.message || "Unknown error"), "error");
      }
    } catch (error) {
      console.error("Error loading appointments:", error);
      this.appointments = [];
      this.filteredAppointments = [];
      this.renderAppointmentsSections();
      this.updateStats();
      this.populateFilterOptions();
      this.showToast("Failed to load appointments. Please refresh the page.", "error");
    } finally {
      this.showLoading(false);
    }
  }

  showLoading(show) {
    const spinner = document.getElementById("loadingSpinner");
    const noData = document.getElementById("noDataMessage");

    if (show) {
      if (spinner) spinner.classList.remove("hidden");
      if (noData) noData.classList.add("hidden");
    } else {
      if (spinner) spinner.classList.add("hidden");
      // Make sure the 'all' section is visible after loading
      setTimeout(() => {
        const allSection = document.getElementById('all-section');
        if (allSection) {
          allSection.classList.add('active');
          allSection.style.display = 'block';
        }
      }, 100);
    }
  }

  renderAppointmentsSections() {
    this.renderAllSection();
    this.renderOverdueSection();
    this.renderPendingSection();
    this.renderPaidSection();
    this.renderCancelledSection();
  }

  renderAllSection() {
    const appointments = this.filteredAppointments;
    this.renderSectionTable('all', appointments, 'All Appointments');
  }

  renderOverdueSection() {
    const appointments = this.filteredAppointments.filter(app => 
      app.PaymentStatus === 'Overdue'
    );
    this.renderSectionTable('overdue', appointments, 'Overdue Appointments');
  }

  renderPendingSection() {
    const appointments = this.filteredAppointments.filter(app => 
      app.PaymentStatus === 'Pending'
    );
    this.renderSectionTable('pending', appointments, 'Pending Appointments');
  }

  renderPaidSection() {
    const appointments = this.filteredAppointments.filter(app => 
      app.PaymentStatus === 'Paid'
    );
    this.renderSectionTable('paid', appointments, 'Paid Appointments');
  }

  renderCancelledSection() {
    const appointments = this.filteredAppointments.filter(app => 
      app.AppointmentStatus === 'Cancelled'
    );
    this.renderSectionTable('cancelled', appointments, 'Cancelled Appointments');
  }

  renderSectionTable(section, appointments, title) {
    const container = document.getElementById(`${section}-appointments-table-content`);
    const paginationContainer = document.getElementById(`${section}-pagination-controls-container`);

    if (!container) {
      console.error(`Container not found: ${section}-appointments-table-content`);
      return;
    }

    if (appointments.length === 0) {
      container.innerHTML = `
        <div class="text-center py-8 text-gray-500">
          <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
          </svg>
          <p>No ${title.toLowerCase()} found</p>
        </div>
      `;
      if (paginationContainer) paginationContainer.classList.add("hidden");
      return;
    }

    if (paginationContainer) paginationContainer.classList.remove("hidden");

    const tableHTML = `
      <div class="glass-card rounded-2xl border-gray-200 border-1 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-semibold text-nhd-blue font-family-bodoni">${title}</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full payment-table" id="${section}PaymentManagementTable" data-section="${section}-appointments">
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
                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientName">
                  Patient 
                  <span class="sort-indicator ml-1">
                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                      <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                      <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                    </svg>
                    <span class="sort-icon-active hidden"></span>
                  </span>
                </th>
                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorName">
                  Doctor 
                  <span class="sort-indicator ml-1">
                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                      <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                      <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                    </svg>
                    <span class="sort-icon-active hidden"></span>
                  </span>
                </th>
                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="Status">
                  Appointment Status 
                  <span class="sort-indicator ml-1">
                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                      <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                      <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                    </svg>
                    <span class="sort-icon-active hidden"></span>
                  </span>
                </th>
                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="Amount">
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
            <tbody id="table-body-${section}-appointments" class="bg-white divide-y divide-gray-200">
              ${this.generateTableRows(appointments)}
            </tbody>
          </table>
        </div>
      </div>
    `;

    container.innerHTML = tableHTML;

    // Initialize SortableTable system for this section
    setTimeout(() => {
      this.initializeSortableTable(section);
    }, 100);
  }

  renderAppointmentsTable() {
    const container = document.getElementById("appointments-table-content");
    const noData = document.getElementById("noDataMessage");
    const paginationContainer = document.getElementById(
      "pagination-controls-container",
    );

    if (this.filteredAppointments.length === 0) {
      container.innerHTML = "";
      noData.classList.remove("hidden");
      if (paginationContainer) paginationContainer.classList.add("hidden");
      return;
    }

    noData.classList.add("hidden");
    if (paginationContainer) paginationContainer.classList.remove("hidden");

    // Create the table HTML structure that matches the PaymentManagement design
    const tableHTML = `
      <div class="glass-card rounded-2xl border-gray-200 border-1 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-xl font-semibold text-nhd-blue font-family-bodoni">Appointments & Payments</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full payment-table" id="paymentManagementTable" data-section="payment-management">
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
                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientName">
                  Patient 
                  <span class="sort-indicator ml-1">
                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                      <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                      <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                    </svg>
                    <span class="sort-icon-active hidden"></span>
                  </span>
                </th>
                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorName">
                  Doctor 
                  <span class="sort-indicator ml-1">
                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                      <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                      <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                    </svg>
                    <span class="sort-icon-active hidden"></span>
                  </span>
                </th>
                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="Status">
                  Appointment Status 
                  <span class="sort-indicator ml-1">
                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                      <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                      <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                    </svg>
                    <span class="sort-icon-active hidden"></span>
                  </span>
                </th>
                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="Amount">
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
            <tbody id="table-body-payment-management" class="bg-white divide-y divide-gray-200">
              ${this.generateTableRows(this.filteredAppointments)}
            </tbody>
          </table>
        </div>
      </div>
    `;

    container.innerHTML = tableHTML;

    // Initialize SortableTable system for payment management
    setTimeout(() => {
      if (window.tableManager) {
        window.tableManager.init();
        // Initialize pagination for payment-management
        if (window.tableManager.paginationManager) {
          window.tableManager.paginationManager.initializeSection("payment-management");
        }
      }
    }, 100);
  }

  generateTableRows(appointments = null) {
    const appointmentsList = appointments || this.filteredAppointments;
    return appointmentsList
      .map((appointment) => {
        const date = new Date(appointment.DateTime);
        let paymentStatus = appointment.PaymentStatus || "No Payment";
        let totalAmount = appointment.TotalAmount
          ? `₱${parseFloat(appointment.TotalAmount).toFixed(2)}`
          : "₱0.00";

        // Handle overdue payments - update status display if overdue
        let overdueIndicator = "";
        let amountDisplay = totalAmount;
        if (appointment.IsOverdue && appointment.OverdueAmount > 0) {
          const originalAmount = `₱${parseFloat(appointment.OriginalAmount).toFixed(2)}`;
          const overdueAmount = `₱${parseFloat(appointment.OverdueAmount).toFixed(2)}`;
          amountDisplay = `
            <div class="text-red-600 font-semibold">${totalAmount}</div>
            <div class="text-xs text-red-500">
              Original: ${originalAmount}<br/>
              + ${overdueAmount} overdue fee
            </div>
          `;
          overdueIndicator =
            '<span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-1" title="Overdue Payment"></span>';
          // Update status to show as overdue if it's pending but actually overdue
          if (paymentStatus === "Pending") {
            paymentStatus = "Overdue";
          }
        }

        // Check if appointment is cancelled to restrict actions
        const isCancelled = appointment.AppointmentStatus === 'Cancelled';
        const appointmentStatusClass = this.getAppointmentStatusClass(appointment.AppointmentStatus);

        return `
          <tr class="hover:bg-gray-50 transition-colors table-row" 
              data-patient-name="${appointment.PatientName || ""}"
              data-doctor-name="${appointment.DoctorName || ""}"
              data-payment-status="${paymentStatus}"
              data-appointment-status="${appointment.AppointmentStatus || ""}"
              data-total-amount="${appointment.TotalAmount || 0}"
              data-date-time="${appointment.DateTime}">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">
                ${date.toLocaleDateString()}
              </div>
              <div class="text-sm text-gray-500">
                ${date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">${overdueIndicator}${appointment.PatientName || "Unknown"}</div>
              <div class="text-sm text-gray-500">${appointment.PatientEmail || ""}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">${appointment.DoctorName || "Unknown"}</div>
              <div class="text-sm text-gray-500">${appointment.Specialization || ""}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="text-sm status-badge ${appointmentStatusClass}">${appointment.AppointmentStatus || "Unknown"}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">${amountDisplay}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="text-sm status-badge status-${paymentStatus.toLowerCase().replace(" ", "-")}">${overdueIndicator}${paymentStatus}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-1">
                ${
                  isCancelled ? `
                    <span class="text-xs text-gray-500 italic">No actions available for cancelled appointments</span>
                  ` : `
                    ${
                      appointment.PaymentID
                        ? `
                        <button onclick="paymentManager.openEditPaymentModal(${appointment.AppointmentID}, ${appointment.PaymentID})" 
                                class="glass-card shadow-sm px-4 py-2 text-sm bg-green-600/80 text-white transition-colors rounded-2xl hover:bg-green-600">
                            Edit
                        </button>
                    `
                        : `
                        <button onclick="paymentManager.openAddPaymentModal(${appointment.AppointmentID})" 
                                class="glass-card px-4 py-2 text-sm shadow-sm bg-nhd-blue/80 text-white transition-colors rounded-xl hover:bg-nhd-blue/90">
                            Add Payment
                        </button>
                    `
                    }
                    ${
                      appointment.PaymentID
                        ? `
                        <button onclick="paymentManager.confirmDeletePayment(${appointment.PaymentID})" 
                                class="glass-card shadow-sm px-4 py-2 text-sm bg-red-700/80 text-white transition-colors rounded-2xl hover:bg-red-800/90">
                            Remove
                        </button>
                    `
                        : ""
                    }
                  `
                }
              </div>
            </td>
          </tr>
        `;
      })
      .join("");
  }

  getAppointmentStatusClass(status) {
    switch (status?.toLowerCase()) {
      case 'confirmed':
        return 'status-confirmed bg-green-100 text-green-800';
      case 'pending':
        return 'status-pending bg-yellow-100 text-yellow-800';
      case 'cancelled':
        return 'status-cancelled bg-red-100 text-red-800';
      case 'completed':
        return 'status-completed bg-blue-100 text-blue-800';
      case 'rescheduled':
        return 'status-rescheduled bg-purple-100 text-purple-800';
      default:
        return 'status-unknown bg-gray-100 text-gray-800';
    }
  }

  updateStats() {
    const stats = this.calculateStats();

    document.getElementById("paidCount").textContent = stats.paid;
    document.getElementById("pendingCount").textContent = stats.pending;
    document.getElementById("overdueCount").textContent = stats.overdue;
    document.getElementById("totalRevenue").textContent =
      `₱${stats.totalRevenue.toFixed(2)}`;
  }

  calculateStats() {
    const stats = {
      paid: 0,
      pending: 0,
      overdue: 0,
      totalRevenue: 0,
    };

    this.appointments.forEach((appointment) => {
      const status = appointment.PaymentStatus;
      const amount = parseFloat(appointment.TotalAmount) || 0;

      if (status === "Paid") {
        stats.paid++;
        stats.totalRevenue += amount;
      } else if (
        status === "Overdue" ||
        (status === "Pending" && appointment.IsOverdue)
      ) {
        stats.overdue++;
      } else if (status === "Pending") {
        stats.pending++;
      }
      // Note: Failed, Cancelled, and Refunded payments are not counted in the main stats
    });

    return stats;
  }

  populateFilterOptions() {
    const doctorSelect = document.getElementById("filterDoctor");
    const doctors = [
      ...new Set(
        this.appointments.map((app) => app.DoctorName).filter(Boolean),
      ),
    ];

    doctorSelect.innerHTML =
      '<option value="">All Doctors</option>' +
      doctors
        .map((doctor) => `<option value="${doctor}">${doctor}</option>`)
        .join("");
  }

  applyFilters() {
    const statusFilter = document.getElementById("filterStatus").value;
    const dateRangeFilter = document.getElementById("filterDateRange").value;
    const doctorFilter = document.getElementById("filterDoctor").value;
    const patientSearch = document
      .getElementById("searchPatient")
      .value.toLowerCase();

    this.filteredAppointments = this.appointments.filter((appointment) => {
      if (statusFilter) {
        if (statusFilter === "Overdue") {
          if (
            !(
              appointment.PaymentStatus === "Overdue" ||
              (appointment.PaymentStatus === "Pending" && appointment.IsOverdue)
            )
          ) {
            return false;
          }
        } else if (appointment.PaymentStatus !== statusFilter) {
          return false;
        }
      }

      if (dateRangeFilter) {
        const appointmentDate = new Date(appointment.DateTime);
        const today = new Date();
        const startOfWeek = new Date(
          today.setDate(today.getDate() - today.getDay()),
        );
        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const startOfYear = new Date(today.getFullYear(), 0, 1);

        switch (dateRangeFilter) {
          case "today":
            if (appointmentDate.toDateString() !== new Date().toDateString()) {
              return false;
            }
            break;
          case "week":
            if (appointmentDate < startOfWeek) {
              return false;
            }
            break;
          case "month":
            if (appointmentDate < startOfMonth) {
              return false;
            }
            break;
          case "year":
            if (appointmentDate < startOfYear) {
              return false;
            }
            break;
        }
      }

      if (doctorFilter && appointment.DoctorName !== doctorFilter) {
        return false;
      }

      if (patientSearch) {
        const patientName = (appointment.PatientName || "").toLowerCase();
        if (!patientName.includes(patientSearch)) {
          return false;
        }
      }

      return true;
    });

    this.renderAppointmentsSections();
    this.updateStats();

    // Refresh pagination and sorting after filtering
    setTimeout(() => {
      if (window.tableManager && window.tableManager.paginationManager) {
        window.tableManager.paginationManager.refreshPagination(
          "payment-management",
        );
      }
    }, 150);
  }

  async openAddPaymentModal(appointmentId) {
    try {
      const response = await fetch(
        `${window.BASE_URL}/dentalassistant/get-payment-details?appointment_id=${appointmentId}`,
      );
      const data = await response.json();

      if (data.success) {
        this.currentAppointment = data.appointment;
        this.currentPayment = null;
        this.paymentItems = [];
        this.isEditMode = false;

        this.populateModal();
        this.openModal("Add Payment");
      } else {
        this.showToast(
          "Error loading appointment details: " + data.message,
          "error",
        );
      }
    } catch (error) {
      console.error("Error loading appointment details:", error);
      this.showToast("Failed to load appointment details", "error");
    }
  }

  async openEditPaymentModal(appointmentId, paymentId) {
    try {
      const response = await fetch(
        `${window.BASE_URL}/dentalassistant/get-payment-details?payment_id=${paymentId}`,
      );
      const data = await response.json();

      if (data.success && data.payment) {
        this.currentPayment = data.payment;
        this.currentAppointment = data.payment;
        this.paymentItems = data.payment.items || [];
        this.isEditMode = true;

        this.populateModal();
        this.openModal("Edit Payment");
      } else {
        this.showToast(
          "Error loading payment details: " + data.message,
          "error",
        );
      }
    } catch (error) {
      console.error("Error loading payment details:", error);
      this.showToast("Failed to load payment details", "error");
    }
  }

  populateModal() {
    const appointmentData = this.currentPayment || this.currentAppointment;

    document.getElementById("modalPatientName").textContent =
      appointmentData.PatientName || "Unknown";
    document.getElementById("modalDoctorName").textContent =
      appointmentData.DoctorName || "Unknown";
    document.getElementById("modalDateTime").textContent = new Date(
      appointmentData.DateTime || appointmentData.AppointmentDateTime,
    ).toLocaleString();
    document.getElementById("modalAppointmentType").textContent =
      appointmentData.AppointmentType || "Unknown";

    if (this.currentPayment) {
      document.getElementById("paymentStatus").value =
        this.currentPayment.Status || "Pending";
      document.getElementById("paymentNotes").value =
        this.currentPayment.Notes || "";
      document.getElementById("paymentMethod").value =
        this.currentPayment.PaymentMethod || "Cash";
      document.getElementById("deadlineDate").value =
        this.currentPayment.DeadlineDate || "";
      document.getElementById("proofOfPayment").value =
        this.currentPayment.ProofOfPayment || "";
      document.getElementById("deletePaymentBtn").classList.remove("hidden");
    } else {
      document.getElementById("paymentStatus").value = "Pending";
      document.getElementById("paymentNotes").value = "";
      document.getElementById("paymentMethod").value = "Cash";
      document.getElementById("deadlineDate").value = "";
      document.getElementById("proofOfPayment").value = "";
      document.getElementById("deletePaymentBtn").classList.add("hidden");
    }

    this.updateQuickActionButtons(
      document.getElementById("paymentStatus").value,
    );
    this.renderPaymentItems();
    this.updateOverdueDisplayInModal();
  }

  updateOverdueDisplayInModal() {
    // Find existing overdue info display and remove it
    const existingOverdueInfo = document.getElementById("overdueInfoDisplay");
    if (existingOverdueInfo) {
      existingOverdueInfo.remove();
    }

    // Check if current payment has overdue calculations
    if (
      this.currentPayment &&
      this.currentPayment.is_overdue &&
      this.currentPayment.overdue_amount > 0
    ) {
      const appointmentInfo = document.getElementById("appointmentInfo");
      const overdueInfoHTML = `
        <div id="overdueInfoDisplay" class="glass-card border-red-200 border-2 bg-red-50/50 p-4 shadow-sm rounded-xl my-4">
          <h4 class="font-semibold text-red-700 mb-2 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            Overdue Payment Notice
          </h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
              <span class="text-red-600 font-medium">Original Amount:</span>
              <div class="font-semibold text-gray-900">₱${parseFloat(this.currentPayment.original_amount || 0).toFixed(2)}</div>
            </div>
            <div>
              <span class="text-red-600 font-medium">Overdue Fee:</span>
              <div class="font-bold text-red-700">+ ₱${parseFloat(this.currentPayment.overdue_amount || 0).toFixed(2)}</div>
            </div>
            <div>
              <span class="text-red-600 font-medium">Total Amount Due:</span>
              <div class="font-bold text-red-700 text-lg">₱${parseFloat(this.currentPayment.total_amount || 0).toFixed(2)}</div>
            </div>
          </div>
          <div class="text-xs text-red-600 mt-3 font-medium">
            ⚠️ This payment is overdue and has incurred additional fees according to clinic policy.
          </div>
        </div>
      `;
      appointmentInfo.insertAdjacentHTML("afterend", overdueInfoHTML);
    }
  }

  renderPaymentItems() {
    const container = document.getElementById("paymentItemsContainer");

    if (this.paymentItems.length === 0) {
      this.addPaymentItem();
      return;
    }

    container.innerHTML = this.paymentItems
      .map(
        (item, index) => `
            <div class="glass-card p-4 rounded-xl border-1 shadow-sm ${item.TreatmentItemID ? "border-blue-300 bg-blue-50/30" : "border-gray-200"}" data-index="${index}">
                ${
                  item.TreatmentItemID
                    ? `
                    <div class="flex items-center mb-3 text-sm text-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        From Treatment Plan
                    </div>
                `
                    : ""
                }
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <input type="text" value="${item.Description || ""}" 
                               ${item.TreatmentItemID ? "readonly" : 'onchange="paymentManager.updatePaymentItem(' + index + ", 'description', this.value)\""}
                               class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none ${item.TreatmentItemID ? "bg-gray-100 cursor-not-allowed" : "focus:ring-2 focus:ring-nhd-blue/20"}"
                               placeholder="Service description...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount (₱)</label>
                        <input type="number" value="${item.Amount || 0}" step="0.01" min="0"
                               ${item.TreatmentItemID ? "readonly" : 'onchange="paymentManager.updatePaymentItem(' + index + ", 'amount', this.value)\""}
                               class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none ${item.TreatmentItemID ? "bg-gray-100 cursor-not-allowed" : "focus:ring-2 focus:ring-nhd-blue/20"}">
                    </div>
                    <div class="flex items-end space-x-2">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
                            <input type="number" value="${item.Quantity || 1}" min="1"
                                   ${item.TreatmentItemID ? "readonly" : 'onchange="paymentManager.updatePaymentItem(' + index + ", 'quantity', this.value)\""}
                                   class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none ${item.TreatmentItemID ? "bg-gray-100 cursor-not-allowed" : "focus:ring-2 focus:ring-nhd-blue/20"}">
                        </div>
                        ${
                          item.TreatmentItemID
                            ? `
                            <button disabled title="Cannot delete - originates from treatment plan" 
                                    class="glass-card bg-gray-400/50 p-3 text-gray-600 rounded-2xl cursor-not-allowed">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        `
                            : `
                            <button onclick="paymentManager.removePaymentItem(${index})" 
                                    class="glass-card bg-red-600/80 p-3 text-white hover:bg-red-600 rounded-2xl transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        `
                        }
                    </div>
                </div>
                <div class="mt-2 text-right">
                    <span class="text-sm text-gray-600">Total: </span>
                    <span class="font-semibold text-nhd-blue">₱${((item.Amount || 0) * (item.Quantity || 1)).toFixed(2)}</span>
                </div>
            </div>
        `,
      )
      .join("");

    this.updateTotalAmount();
  }

  addPaymentItem() {
    this.paymentItems.push({
      Description: "",
      Amount: 0,
      Quantity: 1,
      Total: 0,
      PaymentItemID: null,
    });
    this.renderPaymentItems();
  }

  updatePaymentItem(index, field, value) {
    if (index >= 0 && index < this.paymentItems.length) {
      this.paymentItems[index][field.charAt(0).toUpperCase() + field.slice(1)] =
        field === "description" ? value : parseFloat(value) || 0;

      // Update total for this item
      this.paymentItems[index].Total =
        (this.paymentItems[index].Amount || 0) *
        (this.paymentItems[index].Quantity || 1);

      this.updateTotalAmount();
    }
  }

  async removePaymentItem(index) {
    if (this.paymentItems.length > 1) {
      const itemToRemove = this.paymentItems[index];

      // Check if this item came from a treatment plan (cannot be deleted)
      if (itemToRemove.TreatmentItemID) {
        this.showToast(
          "Cannot delete this item - it originates from a completed treatment plan",
          "error",
        );
        return;
      }

      // Confirm deletion
      if (!confirm("Are you sure you want to remove this payment item?")) {
        return;
      }

      if (itemToRemove.PaymentItemID) {
        try {
          const deleteResponse = await fetch(
            `${window.BASE_URL}/dentalassistant/delete-payment-item`,
            {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({ itemId: itemToRemove.PaymentItemID }),
            },
          );

          const deleteResult = await deleteResponse.json();

          if (!deleteResult.success) {
            this.showToast(
              "Failed to delete payment item: " + deleteResult.message,
              "error",
            );
            return;
          }

          this.showToast("Payment item deleted successfully", "success");
        } catch (error) {
          console.error("Error deleting payment item:", error);
          this.showToast("Failed to delete payment item", "error");
          return;
        }
      }

      this.paymentItems.splice(index, 1);
      this.renderPaymentItems();
    } else {
      this.showToast("At least one payment item is required", "warning");
    }
  }

  updateTotalAmount() {
    const subtotal = this.paymentItems.reduce((sum, item) => {
      return sum + (item.Amount || 0) * (item.Quantity || 1);
    }, 0);

    // Check if this is an overdue payment and add overdue fee
    let total = subtotal;
    let totalDisplay = `₱${total.toFixed(2)}`;

    if (
      this.currentPayment &&
      this.currentPayment.is_overdue &&
      this.currentPayment.overdue_amount > 0
    ) {
      // For overdue payments, show breakdown in total display
      const overdueAmount = parseFloat(this.currentPayment.overdue_amount || 0);
      total = subtotal + overdueAmount;
      totalDisplay = `
        <div class="text-right">
          <div class="text-2xl font-bold text-red-700">₱${total.toFixed(2)}</div>
          <div class="text-sm text-red-600">
            Subtotal: ₱${subtotal.toFixed(2)}<br/>
            + Overdue Fee: ₱${overdueAmount.toFixed(2)}
          </div>
        </div>
      `;
    }

    document.getElementById("totalAmount").innerHTML = totalDisplay;
  }

  updateQuickActionButtons(status) {
    const markPaidBtn = document.getElementById("markAsPaidBtn");
    const markPendingBtn = document.getElementById("markAsPendingBtn");

    if (status === "Paid") {
      markPaidBtn.classList.add("hidden");
      markPendingBtn.classList.remove("hidden");
    } else {
      markPaidBtn.classList.remove("hidden");
      markPendingBtn.classList.add("hidden");
    }
  }

  async savePayment() {
    const action = this.isEditMode ? "update" : "create";
    const confirmMessage = this.isEditMode
      ? "Are you sure you want to save changes to this payment?"
      : "Are you sure you want to create this payment?";

    if (!confirm(confirmMessage)) {
      return;
    }

    try {
      const status = document.getElementById("paymentStatus").value;
      const notes = document.getElementById("paymentNotes").value;
      const paymentMethod = document.getElementById("paymentMethod").value;
      const deadlineDate = document.getElementById("deadlineDate").value;
      const proofOfPayment = document.getElementById("proofOfPayment").value;

      const validItems = this.paymentItems.filter(
        (item) =>
          item.Description && item.Description.trim() && item.Amount > 0,
      );

      if (validItems.length === 0) {
        this.showToast("Please add at least one valid payment item", "warning");
        return;
      }

      if (this.isEditMode && this.currentPayment) {
        await this.updateExistingPayment(
          status,
          notes,
          paymentMethod,
          deadlineDate,
          proofOfPayment,
          validItems,
        );
      } else {
        await this.createNewPayment(
          status,
          notes,
          paymentMethod,
          deadlineDate,
          proofOfPayment,
          validItems,
        );
      }
    } catch (error) {
      console.error("Error saving payment:", error);
      this.showToast("Failed to save payment", "error");
    }
  }

  async createNewPayment(
    status,
    notes,
    paymentMethod,
    deadlineDate,
    proofOfPayment,
    items,
  ) {
    const response = await fetch(
      `${window.BASE_URL}/dentalassistant/create-payment`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          appointmentId: this.currentAppointment.AppointmentID,
          patientId: this.currentAppointment.PatientID,
          status: status,
          notes: notes,
          paymentMethod: paymentMethod,
          deadlineDate: deadlineDate,
          proofOfPayment: proofOfPayment,
          items: items.map((item) => ({
            description: item.Description,
            amount: item.Amount,
            quantity: item.Quantity,
          })),
        }),
      },
    );

    const data = await response.json();

    if (data.success) {
      this.showToast("Payment created successfully!", "success");
      this.closeModal();
      this.loadAppointments();
    } else {
      this.showToast("Error creating payment: " + data.message, "error");
    }
  }

  async updateExistingPayment(
    status,
    notes,
    paymentMethod,
    deadlineDate,
    proofOfPayment,
    items,
  ) {
    // Update payment status and notes
    const updateResponse = await fetch(
      `${window.BASE_URL}/dentalassistant/update-payment`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          paymentId: this.currentPayment.PaymentID,
          status: status,
          notes: notes,
          paymentMethod: paymentMethod,
          deadlineDate: deadlineDate,
          proofOfPayment: proofOfPayment,
        }),
      },
    );

    const updateData = await updateResponse.json();

    if (!updateData.success) {
      this.showToast("Error updating payment: " + updateData.message, "error");
      return;
    }

    await this.updatePaymentItems(items);

    this.showToast("Payment updated successfully!", "success");
    this.closeModal();
    this.loadAppointments();
  }

  async updatePaymentItems(items) {
    const paymentId = this.currentPayment.PaymentID;

    const currentItemIds = items
      .map((item) => item.PaymentItemID)
      .filter(Boolean);
    const originalItemIds = this.currentPayment.items.map(
      (item) => item.PaymentItemID,
    );

    for (const itemId of originalItemIds) {
      if (!currentItemIds.includes(itemId)) {
        console.log("Attempting to delete item ID: ", itemId);

        const deleteResponse = await fetch(
          `${window.BASE_URL}/dentalassistant/delete-payment-item`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ itemId: itemId }),
          },
        );

        const deleteResult = await deleteResponse.json();
        console.log("Delete result:", deleteResult);

        if (!deleteResult.success) {
          console.error("Failed to delete item:", deleteResult.message);
        }
      }
    }

    for (const item of items) {
      if (item.PaymentItemID) {
        await fetch(`${window.BASE_URL}/dentalassistant/update-payment-item`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            itemId: item.PaymentItemID,
            description: item.Description,
            amount: item.Amount,
            quantity: item.Quantity,
          }),
        });
      } else {
        await fetch(`${window.BASE_URL}/dentalassistant/add-payment-item`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            paymentId: paymentId,
            description: item.Description,
            amount: item.Amount,
            quantity: item.Quantity,
          }),
        });
      }
    }
  }

  async updatePaymentStatus(status) {
    if (!this.currentPayment) {
      this.showToast("No payment to update", "warning");
      return;
    }

    try {
      const response = await fetch(
        `${window.BASE_URL}/dentalassistant/update-payment-status`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            paymentId: this.currentPayment.PaymentID,
            status: status,
          }),
        },
      );

      const data = await response.json();

      if (data.success) {
        document.getElementById("paymentStatus").value = status;
        this.updateQuickActionButtons(status);
        this.showToast(`Payment marked as ${status}!`, "success");
      } else {
        this.showToast(
          "Error updating payment status: " + data.message,
          "error",
        );
      }
    } catch (error) {
      console.error("Error updating payment status:", error);
      this.showToast("Failed to update payment status", "error");
    }
  }

  confirmDeletePayment(paymentId) {
    if (
      confirm(
        "Are you sure you want to delete this payment? This action cannot be undone.",
      )
    ) {
      this.deletePaymentById(paymentId);
    }
  }

  async deletePayment() {
    if (!this.currentPayment) {
      this.showToast("No payment to delete", "warning");
      return;
    }

    if (
      confirm(
        "Are you sure you want to delete this payment? This action cannot be undone.",
      )
    ) {
      await this.deletePaymentById(this.currentPayment.PaymentID);
      this.closeModal();
    }
  }

  async deletePaymentById(paymentId) {
    try {
      const response = await fetch(
        `${window.BASE_URL}/dentalassistant/delete-payment`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ paymentId: paymentId }),
        },
      );

      const data = await response.json();

      if (data.success) {
        this.showToast("Payment deleted successfully!", "success");
        this.loadAppointments();
      } else {
        this.showToast("Error deleting payment: " + data.message, "error");
      }
    } catch (error) {
      console.error("Error deleting payment:", error);
      this.showToast("Failed to delete payment", "error");
    }
  }

  openModal(title) {
    document.getElementById("modalTitle").textContent = title;
    document.getElementById("paymentModal").classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }

  closeModal() {
    document.getElementById("paymentModal").classList.add("hidden");
    document.body.style.overflow = "auto";

    this.currentPayment = null;
    this.currentAppointment = null;
    this.paymentItems = [];
    this.isEditMode = false;
  }

  showToast(message, type = "info") {
    if (window.toast) {
      window.toast.show(message, type, 5000);
    } else {
      console.log("Toast:", message, `(${type})`);
    }
  }

  initializeSortableTable(section) {
    const tableId = `${section}PaymentManagementTable`;
    const table = document.getElementById(tableId);
    
    if (!table) {
      console.error(`Table not found: ${tableId}`);
      return;
    }

    console.log(`Initializing sortable table for section: ${section}`);
    console.log(`Table manager available:`, !!window.tableManager);
    
    // Initialize the global SortableTable manager if it exists
    if (window.tableManager) {
      console.log('Initializing table manager...');
      window.tableManager.init();
    } else {
      console.warn('SortableTable manager not found. Retrying in 500ms...');
      setTimeout(() => this.initializeSortableTable(section), 500);
      return;
    }

    const paginationContainer = document.getElementById(`${section}-pagination-controls-container`);
    if (paginationContainer) {
      paginationContainer.classList.remove("hidden");
      console.log(`Pagination container shown for ${section}`);
    } else {
      console.warn(`Pagination container not found: ${section}-pagination-controls-container`);
    }

    if (window.tableManager && window.tableManager.paginationManager) {
      const sectionId = `${section}-appointments`;
      console.log(`Initializing pagination for section: ${sectionId}`);
      window.tableManager.paginationManager.initializeSection(sectionId);
    }

    console.log(`Initialized SortableTable for section: ${section}`);
  }

  getSectionAppointments(section) {
    switch(section) {
      case 'all':
        return this.filteredAppointments;
      case 'overdue':
        return this.filteredAppointments.filter(app => app.PaymentStatus === 'Overdue');
      case 'pending':
        return this.filteredAppointments.filter(app => app.PaymentStatus === 'Pending');
      case 'paid':
        return this.filteredAppointments.filter(app => app.PaymentStatus === 'Paid');
      case 'cancelled':
        return this.filteredAppointments.filter(app => app.AppointmentStatus === 'Cancelled');
      default:
        return this.filteredAppointments;
    }
  }

}

// Make the class available globally
window.PaymentManagement = window.PaymentManagement || PaymentManagement;
}

// Initialize payment manager when DOM is ready
let paymentManager;

document.addEventListener("DOMContentLoaded", function () {
  // Add a small delay to ensure all elements are properly loaded
  setTimeout(() => {
    console.log("Initializing PaymentManagement...");
    console.log("SortableTable manager available:", !!window.tableManager);
    
    paymentManager = new window.PaymentManagement();
    
    // Initialize SortableTable system after PaymentManagement is loaded
    if (window.tableManager) {
      console.log("Initializing SortableTable system...");
      window.tableManager.init();
    } else {
      console.warn("SortableTable system not available");
    }
  }, 200);
});
