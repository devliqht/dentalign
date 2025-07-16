// Global variable for the currently open appointment modal
let currentAppointmentId = null;

/**
 * NEW: Dynamically updates the available time slots based on the selected date.
 * If the selected date is today, it disables past time slots.
 */
function updateAvailableTimes() {
  const dateInput = document.getElementById('newAppointmentDate');
  const timeSelect = document.getElementById('newAppointmentTime');
  const selectedDate = new Date(dateInput.value + 'T00:00:00');
  const now = new Date();

  // Reset 'today' to the beginning of the day for accurate date comparison
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const previousTime = timeSelect.value; // Preserve selected time if still valid
  timeSelect.innerHTML = '<option value="">Select a time slot...</option>';

  const timeSlots = [
    { value: "08:00:00", display: "8:00 AM" },
    { value: "09:00:00", display: "9:00 AM" },
    { value: "10:00:00", display: "10:00 AM" },
    { value: "11:00:00", display: "11:00 AM" },
    { value: "12:00:00", display: "12:00 PM" },
    { value: "13:00:00", display: "1:00 PM" },
    { value: "14:00:00", display: "2:00 PM" },
    { value: "15:00:00", display: "3:00 PM" },
    { value: "16:00:00", display: "4:00 PM" },
    { value: "17:00:00", display: "5:00 PM" },
  ];

  timeSlots.forEach(time => {
    const option = document.createElement('option');
    option.value = time.value;
    option.textContent = time.display;

    // Disable the option if the selected date is today and the time slot is in the past
    if (selectedDate.getTime() === today.getTime()) {
      const [slotHour, slotMinute] = time.value.split(':').map(Number);
      if (now.getHours() > slotHour || (now.getHours() === slotHour && now.getMinutes() > slotMinute)) {
        option.disabled = true;
      }
    }
    timeSelect.appendChild(option);
  });
  
  // Restore previously selected time if it's not disabled
  if (previousTime && !timeSelect.querySelector(`option[value="${previousTime}"]`).disabled) {
    timeSelect.value = previousTime;
  }
}

// NEW FUNCTION: Checks the date and sets button visibility
function updateRescheduleButtonVisibility(dateTimeString) {
  const rescheduleButton = document.getElementById('toggleRescheduleBtn');
  const appointmentDate = new Date(dateTimeString);
  const now = new Date();

  if (appointmentDate < now) {
    // If appointment is in the past, hide the button
    rescheduleButton.classList.add('hidden');
  } else {
    // Otherwise, make sure the button is visible
    rescheduleButton.classList.remove('hidden');
  }
}

// MODIFIED FUNCTION: Toggles the form and updates available times
function toggleRescheduleForm() {
  const rescheduleSection = document.getElementById('rescheduleSection');
  const isHidden = rescheduleSection.classList.contains('hidden');
  const dateInput = document.getElementById('newAppointmentDate');

  if (isHidden) {
    dateInput.min = new Date().toISOString().split("T")[0];
    // Set the default value to today to ensure time validation runs on open
    if (!dateInput.value) {
      dateInput.value = new Date().toISOString().split("T")[0];
    }
    updateAvailableTimes(); // IMPORTANT: Update times when the form is shown
  }

  rescheduleSection.classList.toggle('hidden');
}

// MODIFIED FUNCTION: Handles the reschedule form submission with added validation
async function submitReschedule(event) {
  event.preventDefault();

  const statusDiv = document.getElementById('rescheduleStatus');
  const confirmBtn = document.getElementById('confirmRescheduleBtn');

  const appointmentId = document.getElementById('rescheduleAppointmentId').value;
  const doctorId = document.getElementById('rescheduleDoctorId').value;
  const newDate = document.getElementById('newAppointmentDate').value;
  const newTime = document.getElementById('newAppointmentTime').value;

  // Final client-side validation to prevent submitting a past date/time
  const selectedDateTime = new Date(`${newDate}T${newTime}`);
  if (selectedDateTime < new Date()) {
    statusDiv.textContent = 'You cannot reschedule to a past date or time.';
    statusDiv.className = 'mt-3 text-sm text-center text-red-600 font-medium';
    return; // Stop the submission
  }

  statusDiv.textContent = 'Checking availability...';
  statusDiv.className = 'mt-3 text-sm text-center text-gray-600 font-medium';
  confirmBtn.disabled = true;
  confirmBtn.textContent = 'Checking...';

  try {
    const response = await fetch(`${window.BASE_URL}/dentalassistant/reschedule-appointment`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        appointmentId: appointmentId,
        doctorId: doctorId,
        newDate: newDate,
        newTime: newTime,
      })
    });

    const data = await response.json();

    if (data.success) {
      statusDiv.textContent = 'Appointment rescheduled successfully!';
      statusDiv.className = 'mt-3 text-sm text-center text-green-600 font-medium';
      if (window.toast) {
        window.toast.success('Appointment rescheduled!');
      }
      setTimeout(() => {
        closeAppointmentDetailsModal();
        location.reload(); // Reload the main page to see the changes
      }, 1500);
    } else {
      statusDiv.textContent = data.message || 'An unknown error occurred.';
      statusDiv.className = 'mt-3 text-sm text-center text-red-600 font-medium';
    }

  } catch (error) {
    statusDiv.textContent = 'A network error occurred. Please try again.';
    statusDiv.className = 'mt-3 text-sm text-center text-red-600 font-medium';
    console.error("Reschedule Error:", error);
  } finally {
    confirmBtn.disabled = false;
    confirmBtn.textContent = 'Check Availability & Confirm';
  }
}
// ===== END: RESCHEDULING LOGIC =====================================


// ===================================================================
// ===== MODAL AND APPOINTMENT DETAILS FUNCTIONS =====================
// ===================================================================

function showAppointmentDetails(appointmentId) {
  openAppointmentDetailsModal(appointmentId);
}

function openAppointmentDetailsModal(appointmentId) {
  currentAppointmentId = appointmentId;
  const modal = document.getElementById("appointmentDetailsModal");
  const loadingDiv = document.getElementById("modalLoading");
  const contentDiv = document.getElementById("modalContent");
  const errorDiv = document.getElementById("modalError");

  modal.classList.remove("hidden");
  loadingDiv.classList.remove("hidden");
  contentDiv.classList.add("hidden");
  errorDiv.classList.add("hidden");

  fetchAppointmentReport(appointmentId);
}

function closeAppointmentDetailsModal() {
  const modal = document.getElementById("appointmentDetailsModal");
  modal.classList.add("hidden");
  currentAppointmentId = null;

  const form = document.getElementById("appointmentReportForm");
  if (form) {
    form.reset();
  }
}

async function fetchAppointmentReport(appointmentId) {
  try {
    const response = await fetch(
      `${window.BASE_URL}/doctor/get-appointment-report?appointment_id=${appointmentId}`,
    );
    const data = await response.json();

    if (data.success) {
      populateModal(data.appointment, data.report, data.doctors || []);
      showModalContent();
    } else {
      showModalError(data.message || "Failed to load appointment details");
    }
  } catch (error) {
    console.error("Error fetching appointment report:", error);
    showModalError("Network error occurred while loading appointment details");
  }
}

function populateModal(appointment, report, doctors) {
  document.getElementById("modalPatientName").textContent =
    `${appointment.PatientFirstName} ${appointment.PatientLastName}`;

  document.getElementById("modalDateTime").textContent = formatDateTime(
    appointment.DateTime,
  );

  // Check the appointment date and set the reschedule button's visibility
  updateRescheduleButtonVisibility(appointment.DateTime);

  document.getElementById("modalType").textContent =
    appointment.AppointmentType;

  document.getElementById("modalAppointmentId").textContent =
    `#${String(appointment.AppointmentID).padStart(4, "0")}`;

  document.getElementById("modalReason").textContent =
    appointment.Reason || "No reason specified";

  document.getElementById("modalDoctor").textContent =
    `${appointment.DoctorFirstName} ${appointment.DoctorLastName}`;

  document.getElementById("reportAppointmentId").value =
    appointment.AppointmentID;
  document.getElementById("oralNotes").value = report.oralNotes || "";
  document.getElementById("diagnosis").value = report.diagnosis || "";
  document.getElementById("xrayImages").value = report.xrayImages || "";
  document.getElementById("modalStatus").value =
    appointment.Status || "Pending";

  // Populate doctor dropdown and show doctor change section for dental assistants
  populateDoctorDropdown(doctors, appointment.DoctorID);
  checkUserRoleAndShowDoctorChange();

  // Show treatment plan section if appointment is completed
  const treatmentPlanSection = document.getElementById("treatmentPlanSection");
  if (appointment.Status === "Completed" && report.AppointmentReportID) {
    treatmentPlanSection.classList.remove("hidden");
    document.getElementById("treatmentPlanAppointmentReportId").value =
      report.AppointmentReportID;
  } else {
    treatmentPlanSection.classList.add("hidden");
  }
  
  // Populate reschedule form with current appointment details
  document.getElementById('rescheduleAppointmentId').value = appointment.AppointmentID;
  document.getElementById('rescheduleDoctorId').value = appointment.DoctorID;

  // Hide the reschedule form by default every time a new modal is opened
  document.getElementById('rescheduleSection').classList.add('hidden');
  // Also reset its status message
  document.getElementById('rescheduleStatus').textContent = '';
}

function showModalContent() {
  document.getElementById("modalLoading").classList.add("hidden");
  document.getElementById("modalError").classList.add("hidden");
  document.getElementById("modalContent").classList.remove("hidden");
}

function showModalError(message) {
  document.getElementById("modalLoading").classList.add("hidden");
  document.getElementById("modalContent").classList.add("hidden");
  document.getElementById("modalErrorMessage").textContent = message;
  document.getElementById("modalError").classList.remove("hidden");
}

function formatDateTime(dateTimeString) {
  const date = new Date(dateTimeString);
  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  };
  return date.toLocaleDateString("en-US", options);
}

function populateDoctorDropdown(doctors, currentDoctorId) {
  const select = document.getElementById("modalDoctorSelect");
  if (!select) return;

  select.innerHTML = '<option value="">Select a doctor...</option>';

  doctors.forEach((doctor) => {
    const option = document.createElement("option");
    option.value = doctor.UserID;
    option.textContent = `Dr. ${doctor.FirstName} ${doctor.LastName}${doctor.Specialization ? ` - ${doctor.Specialization}` : ""}`;
    select.appendChild(option);
  });
}

function checkUserRoleAndShowDoctorChange() {
  const isDentalAssistant =
    window.location.pathname.includes("dentalassistant");
  const doctorChangeSection = document.getElementById("doctorChangeSection");

  if (isDentalAssistant && doctorChangeSection) {
    doctorChangeSection.classList.remove("hidden");
  }
}

async function updateAppointmentDoctor(appointmentId, newDoctorId) {
  try {
    const response = await fetch(
      `${window.BASE_URL}/dentalassistant/update-appointment-doctor`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          appointmentId: appointmentId,
          doctorId: newDoctorId,
        }),
      },
    );

    const result = await response.json();

    if (result.success) {
      if (window.toast && typeof window.toast.success === "function") {
        window.toast.success(result.message || "Doctor updated successfully");
      }
      await fetchAppointmentReport(appointmentId);
      document.getElementById("modalDoctorSelect").value = "";
    } else {
      if (window.toast && typeof window.toast.error === "function") {
        window.toast.error(result.message || "Failed to update doctor");
      }
    }
  } catch (error) {
    console.error("Error updating appointment doctor:", error);
    if (window.toast && typeof window.toast.error === "function") {
      window.toast.error("Network error occurred while updating doctor");
    }
  }
}

// ===================================================================
// ===== EVENT LISTENERS =============================================
// ===================================================================

document.addEventListener("DOMContentLoaded", function () {
  
  // NEW: Add event listener to the reschedule date input
  const rescheduleDateInput = document.getElementById('newAppointmentDate');
  if (rescheduleDateInput) {
    rescheduleDateInput.addEventListener('change', updateAvailableTimes);
  }

  // Add event listener for the update doctor button
  const updateDoctorBtn = document.getElementById("updateDoctorBtn");
  if (updateDoctorBtn) {
    updateDoctorBtn.addEventListener("click", function () {
      const appointmentId = currentAppointmentId;
      const newDoctorId = document.getElementById("modalDoctorSelect").value;

      if (!newDoctorId) {
        if (window.toast && typeof window.toast.error === "function") {
          window.toast.error("Please select a doctor");
        }
        return;
      }
      if (!confirm("Are you sure you want to change the doctor for this appointment?")) {
        return;
      }
      updateAppointmentDoctor(appointmentId, newDoctorId);
    });
  }

  // Add event listener for the main appointment report form
  const form = document.getElementById("appointmentReportForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      if (!confirm("Are you sure you want to save this appointment report?")) {
        return;
      }
      const formData = new FormData(form);
      const data = {
        appointmentId: formData.get("appointmentId"),
        oralNotes: formData.get("oralNotes"),
        diagnosis: formData.get("diagnosis"),
        xrayImages: formData.get("xrayImages"),
        status: formData.get("modalStatus"),
      };
      try {
        const response = await fetch(
          `${window.BASE_URL}/doctor/update-appointment-report`,
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
          },
        );
        const result = await response.json();
        if (result.success) {
          if (window.toast && typeof window.toast.success === "function") {
            window.toast.success(result.message || "Appointment report saved successfully");
          }
          closeAppointmentDetailsModal();
        } else {
          if (window.toast && typeof window.toast.error === "function") {
            window.toast.error(result.message || "Failed to save appointment report");
          }
        }
      } catch (error) {
        console.error("Error saving appointment report:", error);
        if (window.toast && typeof window.toast.error === "function") {
          window.toast.error("Network error occurred while saving");
        }
      }
    });
  }
  
    // Add treatment plan form submission handler
  const treatmentPlanForm = document.getElementById("treatmentPlanForm");
  if (treatmentPlanForm) {
    treatmentPlanForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      // ... (treatment plan submission logic remains the same)
    });
  }
});

// Modal closing listeners
document.addEventListener("keydown", function (e) {
  if (e.key === "Escape" && currentAppointmentId) {
    closeAppointmentDetailsModal();
  }
});

document.addEventListener("click", function (e) {
  const modal = document.getElementById("appointmentDetailsModal");
  if (e.target === modal) {
    closeAppointmentDetailsModal();
  }
});

// ===================================================================
// ===== TREATMENT PLAN FUNCTIONS (Unchanged) ========================
// ===================================================================

let treatmentPlanItemIndex = 0;

function addTreatmentPlanItem() {
  const container = document.getElementById("treatmentPlanItems");
  const itemIndex = treatmentPlanItemIndex++;

  const itemHtml = `
    <div class="treatment-plan-item glass-card bg-gray-50/50 border border-gray-200 rounded-lg p-4" data-index="${itemIndex}">
      <div class="flex items-center justify-between mb-3">
        <h6 class="text-sm font-medium text-gray-800">Treatment Item ${itemIndex + 1}</h6>
        <button type="button" onclick="removeTreatmentPlanItem(${itemIndex})" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Tooth Number</label>
          <select name="items[${itemIndex}][toothNumber]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
            <option value="">Select tooth</option>${generateToothOptions()}
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Procedure Code</label>
          <select name="items[${itemIndex}][procedureCode]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
            <option value="">Select procedure</option>${generateProcedureOptions()}
          </select>
        </div>
        <div class="md:col-span-2">
          <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
          <input type="text" name="items[${itemIndex}][description]" placeholder="Treatment description..." class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Estimated Cost</label>
          <input type="number" name="items[${itemIndex}][cost]" placeholder="0.00" step="0.01" min="0" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Scheduled Date</label>
          <input type="date" name="items[${itemIndex}][scheduledDate]" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
        </div>
        <div class="md:col-span-2">
          <label class="flex items-center text-xs text-gray-700">
            <input type="checkbox" name="items[${itemIndex}][isCompleted]" class="mr-2 rounded border-gray-300 text-nhd-blue focus:ring-nhd-blue">Mark as completed
          </label>
          <input type="datetime-local" name="items[${itemIndex}][completedAt]" class="mt-1 w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent" style="display: none;">
        </div>
      </div>
    </div>`;
  container.insertAdjacentHTML("beforeend", itemHtml);

  const checkbox = container.querySelector(`[data-index="${itemIndex}"] input[type="checkbox"]`);
  const datetimeInput = container.querySelector(`[data-index="${itemIndex}"] input[type="datetime-local"]`);

  checkbox.addEventListener("change", function () {
    if (this.checked) {
      datetimeInput.style.display = "block";
      datetimeInput.value = new Date().toISOString().slice(0, 16);
    } else {
      datetimeInput.style.display = "none";
      datetimeInput.value = "";
    }
  });
}

function removeTreatmentPlanItem(index) {
  const item = document.querySelector(`.treatment-plan-item[data-index="${index}"]`);
  if (item) {
    item.remove();
  }
}

function generateToothOptions() {
  const teeth = [
    { num: 1, name: "Upper Right Central Incisor" }, { num: 2, name: "Upper Right Lateral Incisor" }, { num: 3, name: "Upper Right Canine" }, { num: 4, name: "Upper Right First Premolar" }, { num: 5, name: "Upper Right Second Premolar" }, { num: 6, name: "Upper Right First Molar" }, { num: 7, name: "Upper Right Second Molar" }, { num: 8, name: "Upper Right Third Molar" },
    { num: 9, name: "Upper Left Central Incisor" }, { num: 10, name: "Upper Left Lateral Incisor" }, { num: 11, name: "Upper Left Canine" }, { num: 12, name: "Upper Left First Premolar" }, { num: 13, name: "Upper Left Second Premolar" }, { num: 14, name: "Upper Left First Molar" }, { num: 15, name: "Upper Left Second Molar" }, { num: 16, name: "Upper Left Third Molar" },
    { num: 17, name: "Lower Left Central Incisor" }, { num: 18, name: "Lower Left Lateral Incisor" }, { num: 19, name: "Lower Left Canine" }, { num: 20, name: "Lower Left First Premolar" }, { num: 21, name: "Lower Left Second Premolar" }, { num: 22, name: "Lower Left First Molar" }, { num: 23, name: "Lower Left Second Molar" }, { num: 24, name: "Lower Left Third Molar" },
    { num: 25, name: "Lower Right Central Incisor" }, { num: 26, name: "Lower Right Lateral Incisor" }, { num: 27, name: "Lower Right Canine" }, { num: 28, name: "Lower Right First Premolar" }, { num: 29, name: "Lower Right Second Premolar" }, { num: 30, name: "Lower Right First Molar" }, { num: 31, name: "Lower Right Second Molar" }, { num: 32, name: "Lower Right Third Molar" },
  ];
  return teeth.map((tooth) => `<option value="${tooth.num}">#${tooth.num} - ${tooth.name}</option>`).join("");
}

function generateProcedureOptions() {
  const procedures = [
    { code: "D0120", name: "Periodic Oral Examination" }, { code: "D0150", name: "Comprehensive Oral Examination" }, { code: "D0210", name: "Intraoral Periapical X-Ray" }, { code: "D1110", name: "Prophylaxis - Adult" }, { code: "D2140", name: "Amalgam - One Surface" }, { code: "D2330", name: "Resin-Based Composite - One Surface, Anterior" }, { code: "D2391", name: "Resin-Based Composite - One Surface, Posterior" }, { code: "D2740", name: "Crown - Porcelain/Ceramic" }, { code: "D3310", name: "Endodontic Therapy, Anterior Tooth" }, { code: "D3320", name: "Endodontic Therapy, Premolar" }, { code: "D3330", name: "Endodontic Therapy, Molar" }, { code: "D4341", name: "Periodontal Scaling and Root Planing" }, { code: "D6010", name: "Surgical Placement of Implant" }, { code: "D7140", name: "Extraction, Erupted Tooth" }, { code: "D7210", name: "Extraction with Bone Removal" }, { code: "D8090", name: "Comprehensive Orthodontic Treatment" },
  ];
  return procedures.map((proc) => `<option value="${proc.code}">${proc.code} - ${proc.name}</option>`).join("");
}

function cancelTreatmentPlan() {
  const form = document.getElementById("treatmentPlanForm");
  if (form) {
    form.reset();
  }
  const container = document.getElementById("treatmentPlanItems");
  container.innerHTML = "";
  treatmentPlanItemIndex = 0;
  const treatmentPlanSection = document.getElementById("treatmentPlanSection");
  treatmentPlanSection.classList.add("hidden");
}

// Note: The submit handler for the treatment plan form is now inside the DOMContentLoaded listener.