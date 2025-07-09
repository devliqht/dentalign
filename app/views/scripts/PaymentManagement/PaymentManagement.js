class PaymentManagement {
  constructor() {
    this.appointments = [];
    this.filteredAppointments = [];
    this.currentPayment = null;
    this.currentAppointment = null;
    this.paymentItems = [];
    this.isEditMode = false;

    this.init();
  }

  init() {
    this.bindEvents();
    this.loadAppointments();
  }

  bindEvents() {
    document
      .getElementById("refreshDataBtn")
      .addEventListener("click", () => this.loadAppointments());

    document
      .getElementById("closeModal")
      .addEventListener("click", () => this.closeModal());
    document.getElementById("paymentModal").addEventListener("click", (e) => {
      if (e.target.id === "paymentModal") this.closeModal();
    });

    document
      .getElementById("filterStatus")
      .addEventListener("change", () => this.applyFilters());
    document
      .getElementById("filterDateRange")
      .addEventListener("change", () => this.applyFilters());
    document
      .getElementById("filterDoctor")
      .addEventListener("change", () => this.applyFilters());
    document
      .getElementById("searchPatient")
      .addEventListener("input", () => this.applyFilters());

    document
      .getElementById("addItemBtn")
      .addEventListener("click", () => this.addPaymentItem());
    document
      .getElementById("savePaymentBtn")
      .addEventListener("click", () => this.savePayment());
    document
      .getElementById("deletePaymentBtn")
      .addEventListener("click", () => this.deletePayment());
    document
      .getElementById("markAsPaidBtn")
      .addEventListener("click", () => this.updatePaymentStatus("Paid"));
    document
      .getElementById("markAsPendingBtn")
      .addEventListener("click", () => this.updatePaymentStatus("Pending"));

    document.getElementById("paymentStatus").addEventListener("change", (e) => {
      this.updateQuickActionButtons(e.target.value);
    });
  }

  async loadAppointments() {
    try {
      this.showLoading(true);

      const response = await fetch(
        "/dentalign/dentalassistant/get-all-appointments-payments",
      );
      const data = await response.json();

      if (data.success) {
        this.appointments = data.appointments;
        this.filteredAppointments = [...this.appointments];
        this.renderAppointmentsTable();
        this.updateStats();
        this.populateFilterOptions();
      } else {
        this.showToast("Error loading appointments: " + data.message, "error");
      }
    } catch (error) {
      console.error("Error loading appointments:", error);
      this.showToast("Failed to load appointments", "error");
    } finally {
      this.showLoading(false);
    }
  }

  showLoading(show) {
    const spinner = document.getElementById("loadingSpinner");
    const table = document.getElementById("appointmentsTableBody");
    const noData = document.getElementById("noDataMessage");

    if (show) {
      spinner.classList.remove("hidden");
      table.innerHTML = "";
      noData.classList.add("hidden");
    } else {
      spinner.classList.add("hidden");
    }
  }

  renderAppointmentsTable() {
    const tbody = document.getElementById("appointmentsTableBody");
    const noData = document.getElementById("noDataMessage");

    if (this.filteredAppointments.length === 0) {
      tbody.innerHTML = "";
      noData.classList.remove("hidden");
      return;
    }

    noData.classList.add("hidden");

    tbody.innerHTML = this.filteredAppointments
      .map((appointment) => {
        const date = new Date(appointment.DateTime);
        const paymentStatus = appointment.PaymentStatus || "No Payment";
        const totalAmount = appointment.TotalAmount
          ? `₱${parseFloat(appointment.TotalAmount).toFixed(2)}`
          : "$0.00";

        return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            ${date.toLocaleDateString()}
                        </div>
                        <div class="text-sm text-gray-500">
                            ${date.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${appointment.PatientName || "Unknown"}</div>
                        <div class="text-sm text-gray-500">${appointment.PatientEmail || ""}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${appointment.DoctorName || "Unknown"}</div>
                        <div class="text-sm text-gray-500">${appointment.Specialization || ""}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${appointment.AppointmentType}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${totalAmount}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm status-badge status-${paymentStatus.toLowerCase().replace(" ", "-")}">${paymentStatus}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1">
                            ${
                              appointment.PaymentID
                                ? `
                                <button onclick="paymentManager.openEditPaymentModal(${appointment.AppointmentID}, ${appointment.PaymentID})" 
                                        class="glass-card shadow-sm px-4 py-2 text-sm bg-nhd-green/80 text-white transition-colors">
                                    Edit
                                </button>
                            `
                                : `
                                <button onclick="paymentManager.openAddPaymentModal(${appointment.AppointmentID})" 
                                        class="glass-card px-4 py-2 text-sm shadow-sm bg-nhd-blue/80 text-white transition-colors">
                                    Add Payment
                                </button>
                            `
                            }
                            ${
                              appointment.PaymentID
                                ? `
                                <button onclick="paymentManager.confirmDeletePayment(${appointment.PaymentID})" 
                                        class="glass-card shadow-sm px-4 py-2 text-sm bg-red-700/80 text-white transition-colors">
                                    Remove
                                </button>
                            `
                                : ""
                            }
                        </div>
                    </td>
                </tr>
            `;
      })
      .join("");
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
      } else if (status === "Pending") {
        stats.pending++;
      } else if (status === "Overdue") {
        stats.overdue++;
      }
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
    const dateFilter = document.getElementById("filterDateRange").value;
    const doctorFilter = document.getElementById("filterDoctor").value;
    const searchTerm = document
      .getElementById("searchPatient")
      .value.toLowerCase();

    this.filteredAppointments = this.appointments.filter((appointment) => {
      if (statusFilter && appointment.PaymentStatus !== statusFilter)
        return false;

      if (doctorFilter && appointment.DoctorName !== doctorFilter) return false;

      if (
        searchTerm &&
        !appointment.PatientName.toLowerCase().includes(searchTerm)
      )
        return false;

      if (dateFilter) {
        const appointmentDate = new Date(appointment.DateTime);
        const now = new Date();

        switch (dateFilter) {
          case "today":
            if (appointmentDate.toDateString() !== now.toDateString())
              return false;
            break;
          case "week":
            const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
            if (appointmentDate < weekAgo) return false;
            break;
          case "month":
            if (
              appointmentDate.getMonth() !== now.getMonth() ||
              appointmentDate.getFullYear() !== now.getFullYear()
            )
              return false;
            break;
          case "year":
            if (appointmentDate.getFullYear() !== now.getFullYear())
              return false;
            break;
        }
      }

      return true;
    });

    this.renderAppointmentsTable();
  }

  async openAddPaymentModal(appointmentId) {
    try {
      const response = await fetch(
        `/dentalign/dentalassistant/get-payment-details?appointment_id=${appointmentId}`,
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
        `/dentalign/dentalassistant/get-payment-details?payment_id=${paymentId}`,
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
      document.getElementById("deletePaymentBtn").classList.remove("hidden");
    } else {
      document.getElementById("paymentStatus").value = "Pending";
      document.getElementById("paymentNotes").value = "";
      document.getElementById("deletePaymentBtn").classList.add("hidden");
    }

    this.updateQuickActionButtons(
      document.getElementById("paymentStatus").value,
    );
    this.renderPaymentItems();
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
            <div class="glass-card p-4 rounded-xl border-1 shadow-sm border-gray-200" data-index="${index}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <input type="text" value="${item.Description || ""}" 
                               onchange="paymentManager.updatePaymentItem(${index}, 'description', this.value)"
                               class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20"
                               placeholder="Service description...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount (₱)</label>
                        <input type="number" value="${item.Amount || 0}" step="0.01" min="0"
                               onchange="paymentManager.updatePaymentItem(${index}, 'amount', this.value)"
                               class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                    </div>
                    <div class="flex items-end space-x-2">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Qty</label>
                            <input type="number" value="${item.Quantity || 1}" min="1"
                                   onchange="paymentManager.updatePaymentItem(${index}, 'quantity', this.value)"
                                   class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                        </div>
                        <button onclick="paymentManager.removePaymentItem(${index})" 
                                class="p-3 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-xl transition-colors">
                             <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
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

  removePaymentItem(index) {
    if (this.paymentItems.length > 1) {
      this.paymentItems.splice(index, 1);
      this.renderPaymentItems();
    } else {
      this.showToast("At least one payment item is required", "warning");
    }
  }

  updateTotalAmount() {
    const total = this.paymentItems.reduce((sum, item) => {
      return sum + (item.Amount || 0) * (item.Quantity || 1);
    }, 0);

    document.getElementById("totalAmount").textContent = `₱${total.toFixed(2)}`;
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
    try {
      const status = document.getElementById("paymentStatus").value;
      const notes = document.getElementById("paymentNotes").value;

      // Validate payment items
      const validItems = this.paymentItems.filter(
        (item) =>
          item.Description && item.Description.trim() && item.Amount > 0,
      );

      if (validItems.length === 0) {
        this.showToast("Please add at least one valid payment item", "warning");
        return;
      }

      if (this.isEditMode && this.currentPayment) {
        // Update existing payment
        await this.updateExistingPayment(status, notes, validItems);
      } else {
        // Create new payment
        await this.createNewPayment(status, notes, validItems);
      }
    } catch (error) {
      console.error("Error saving payment:", error);
      this.showToast("Failed to save payment", "error");
    }
  }

  async createNewPayment(status, notes, items) {
    const response = await fetch("/dentalign/dentalassistant/create-payment", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        appointmentId: this.currentAppointment.AppointmentID,
        patientId: this.currentAppointment.PatientID,
        status: status,
        notes: notes,
        items: items.map((item) => ({
          description: item.Description,
          amount: item.Amount,
          quantity: item.Quantity,
        })),
      }),
    });

    const data = await response.json();

    if (data.success) {
      this.showToast("Payment created successfully!", "success");
      this.closeModal();
      this.loadAppointments();
    } else {
      this.showToast("Error creating payment: " + data.message, "error");
    }
  }

  async updateExistingPayment(status, notes, items) {
    // Update payment status and notes
    const updateResponse = await fetch(
      "/dentalign/dentalassistant/update-payment",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          paymentId: this.currentPayment.PaymentID,
          status: status,
          notes: notes,
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
          "/dentalign/dentalassistant/delete-payment-item",
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
        await fetch("/dentalign/dentalassistant/update-payment-item", {
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
        await fetch("/dentalign/dentalassistant/add-payment-item", {
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
        "/dentalign/dentalassistant/update-payment-status",
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
        "/dentalign/dentalassistant/delete-payment",
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

    // Reset modal state
    this.currentPayment = null;
    this.currentAppointment = null;
    this.paymentItems = [];
    this.isEditMode = false;
  }

  showToast(message, type = "info") {
    if (window.toast) {
      switch (type) {
        case "success":
          window.toast.success(message);
          break;
        case "error":
          window.toast.error(message);
          break;
        case "warning":
          window.toast.warning(message);
          break;
        case "info":
        default:
          window.toast.info(message);
          break;
      }
    } else {
      console.warn("Toast system not available, falling back to alert");
      alert(message);
    }
  }
}

let paymentManager;
document.addEventListener("DOMContentLoaded", function () {
  paymentManager = new PaymentManagement();
});
