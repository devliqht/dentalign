let currentAppointmentId = null;

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
      populateModal(data.appointment, data.report);
      showModalContent();
    } else {
      showModalError(data.message || "Failed to load appointment details");
    }
  } catch (error) {
    console.error("Error fetching appointment report:", error);
    showModalError("Network error occurred while loading appointment details");
  }
}

function populateModal(appointment, report) {
  document.getElementById("modalPatientName").textContent =
    `${appointment.PatientFirstName} ${appointment.PatientLastName}`;

  document.getElementById("modalDateTime").textContent = formatDateTime(
    appointment.DateTime,
  );

  document.getElementById("modalType").textContent =
    appointment.AppointmentType;

  document.getElementById("modalAppointmentId").textContent =
    `#${String(appointment.AppointmentID).padStart(4, "0")}`;

  document.getElementById("modalReason").textContent =
    appointment.Reason || "No reason specified";

  document.getElementById("reportAppointmentId").value =
    appointment.AppointmentID;
  document.getElementById("oralNotes").value = report.oralNotes || "";
  document.getElementById("diagnosis").value = report.diagnosis || "";
  document.getElementById("xrayImages").value = report.xrayImages || "";
  document.getElementById("modalStatus").value =
    appointment.Status || "Pending";

  // Show treatment plan section if appointment is completed
  const treatmentPlanSection = document.getElementById("treatmentPlanSection");
  if (appointment.Status === "Completed" && report.AppointmentReportID) {
    treatmentPlanSection.classList.remove("hidden");
    document.getElementById("treatmentPlanAppointmentReportId").value =
      report.AppointmentReportID;
  } else {
    treatmentPlanSection.classList.add("hidden");
  }
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

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("appointmentReportForm");
  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();

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
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
          },
        );

        const result = await response.json();

        if (result.success) {
          if (window.toast && typeof window.toast.success === "function") {
            window.toast.success(
              result.message || "Appointment report saved successfully",
            );
          }
          closeAppointmentDetailsModal();
        } else {
          if (window.toast && typeof window.toast.error === "function") {
            window.toast.error(
              result.message || "Failed to save appointment report",
            );
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
});

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

// ===== TREATMENT PLAN FUNCTIONS =====

let treatmentPlanItemIndex = 0;

function addTreatmentPlanItem() {
  const container = document.getElementById("treatmentPlanItems");
  const itemIndex = treatmentPlanItemIndex++;

  const itemHtml = `
    <div class="treatment-plan-item glass-card bg-gray-50/50 border border-gray-200 rounded-lg p-4" data-index="${itemIndex}">
      <div class="flex items-center justify-between mb-3">
        <h6 class="text-sm font-medium text-gray-800">Treatment Item ${itemIndex + 1}</h6>
        <button type="button" onclick="removeTreatmentPlanItem(${itemIndex})" 
                class="text-red-600 hover:text-red-800 text-sm">
          Remove
        </button>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Tooth Number</label>
          <select name="items[${itemIndex}][toothNumber]" 
                  class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
            <option value="">Select tooth</option>
            ${generateToothOptions()}
          </select>
        </div>
        
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Procedure Code</label>
          <select name="items[${itemIndex}][procedureCode]" 
                  class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
            <option value="">Select procedure</option>
            ${generateProcedureOptions()}
          </select>
        </div>
        
        <div class="md:col-span-2">
          <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
          <input type="text" 
                 name="items[${itemIndex}][description]" 
                 placeholder="Treatment description..."
                 class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
        </div>
        
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Estimated Cost</label>
          <input type="number" 
                 name="items[${itemIndex}][cost]" 
                 placeholder="0.00" 
                 step="0.01" 
                 min="0"
                 class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
        </div>
        
        <div>
          <label class="block text-xs font-medium text-gray-700 mb-1">Scheduled Date</label>
          <input type="date" 
                 name="items[${itemIndex}][scheduledDate]"
                 class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
        </div>
        
        <div class="md:col-span-2">
          <label class="flex items-center text-xs text-gray-700">
            <input type="checkbox" 
                   name="items[${itemIndex}][isCompleted]" 
                   class="mr-2 rounded border-gray-300 text-nhd-blue focus:ring-nhd-blue">
            Mark as completed
          </label>
          <input type="datetime-local" 
                 name="items[${itemIndex}][completedAt]" 
                 class="mt-1 w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent"
                 style="display: none;">
        </div>
      </div>
    </div>
  `;

  container.insertAdjacentHTML("beforeend", itemHtml);

  // Add event listener for checkbox
  const checkbox = container.querySelector(
    `[data-index="${itemIndex}"] input[type="checkbox"]`,
  );
  const datetimeInput = container.querySelector(
    `[data-index="${itemIndex}"] input[type="datetime-local"]`,
  );

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
  const item = document.querySelector(
    `.treatment-plan-item[data-index="${index}"]`,
  );
  if (item) {
    item.remove();
  }
}

function generateToothOptions() {
  const teeth = [
    // Upper right quadrant (1-8)
    { num: 1, name: "Upper Right Central Incisor" },
    { num: 2, name: "Upper Right Lateral Incisor" },
    { num: 3, name: "Upper Right Canine" },
    { num: 4, name: "Upper Right First Premolar" },
    { num: 5, name: "Upper Right Second Premolar" },
    { num: 6, name: "Upper Right First Molar" },
    { num: 7, name: "Upper Right Second Molar" },
    { num: 8, name: "Upper Right Third Molar" },

    // Upper left quadrant (9-16)
    { num: 9, name: "Upper Left Central Incisor" },
    { num: 10, name: "Upper Left Lateral Incisor" },
    { num: 11, name: "Upper Left Canine" },
    { num: 12, name: "Upper Left First Premolar" },
    { num: 13, name: "Upper Left Second Premolar" },
    { num: 14, name: "Upper Left First Molar" },
    { num: 15, name: "Upper Left Second Molar" },
    { num: 16, name: "Upper Left Third Molar" },

    // Lower left quadrant (17-24)
    { num: 17, name: "Lower Left Central Incisor" },
    { num: 18, name: "Lower Left Lateral Incisor" },
    { num: 19, name: "Lower Left Canine" },
    { num: 20, name: "Lower Left First Premolar" },
    { num: 21, name: "Lower Left Second Premolar" },
    { num: 22, name: "Lower Left First Molar" },
    { num: 23, name: "Lower Left Second Molar" },
    { num: 24, name: "Lower Left Third Molar" },

    // Lower right quadrant (25-32)
    { num: 25, name: "Lower Right Central Incisor" },
    { num: 26, name: "Lower Right Lateral Incisor" },
    { num: 27, name: "Lower Right Canine" },
    { num: 28, name: "Lower Right First Premolar" },
    { num: 29, name: "Lower Right Second Premolar" },
    { num: 30, name: "Lower Right First Molar" },
    { num: 31, name: "Lower Right Second Molar" },
    { num: 32, name: "Lower Right Third Molar" },
  ];

  return teeth
    .map(
      (tooth) =>
        `<option value="${tooth.num}">#${tooth.num} - ${tooth.name}</option>`,
    )
    .join("");
}

function generateProcedureOptions() {
  const procedures = [
    { code: "D0120", name: "Periodic Oral Examination" },
    { code: "D0150", name: "Comprehensive Oral Examination" },
    { code: "D0210", name: "Intraoral Periapical X-Ray" },
    { code: "D1110", name: "Prophylaxis - Adult" },
    { code: "D2140", name: "Amalgam - One Surface" },
    { code: "D2330", name: "Resin-Based Composite - One Surface, Anterior" },
    { code: "D2391", name: "Resin-Based Composite - One Surface, Posterior" },
    { code: "D2740", name: "Crown - Porcelain/Ceramic" },
    { code: "D3310", name: "Endodontic Therapy, Anterior Tooth" },
    { code: "D3320", name: "Endodontic Therapy, Premolar" },
    { code: "D3330", name: "Endodontic Therapy, Molar" },
    { code: "D4341", name: "Periodontal Scaling and Root Planing" },
    { code: "D6010", name: "Surgical Placement of Implant" },
    { code: "D7140", name: "Extraction, Erupted Tooth" },
    { code: "D7210", name: "Extraction with Bone Removal" },
    { code: "D8090", name: "Comprehensive Orthodontic Treatment" },
  ];

  return procedures
    .map(
      (proc) =>
        `<option value="${proc.code}">${proc.code} - ${proc.name}</option>`,
    )
    .join("");
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

// Add treatment plan form submission handler
document.addEventListener("DOMContentLoaded", function () {
  const treatmentPlanForm = document.getElementById("treatmentPlanForm");
  if (treatmentPlanForm) {
    treatmentPlanForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(treatmentPlanForm);
      const items = [];

      // Collect treatment plan items
      const itemElements = document.querySelectorAll(".treatment-plan-item");
      itemElements.forEach((item, index) => {
        const toothNumber = item.querySelector(
          'select[name*="[toothNumber]"]',
        ).value;
        const procedureCode = item.querySelector(
          'select[name*="[procedureCode]"]',
        ).value;
        const description = item.querySelector(
          'input[name*="[description]"]',
        ).value;
        const cost = item.querySelector('input[name*="[cost]"]').value;
        const scheduledDate = item.querySelector(
          'input[name*="[scheduledDate]"]',
        ).value;
        const isCompleted = item.querySelector(
          'input[name*="[isCompleted]"]',
        ).checked;
        const completedAt = item.querySelector(
          'input[name*="[completedAt]"]',
        ).value;

        if (toothNumber || procedureCode || description) {
          items.push({
            toothNumber: toothNumber,
            procedureCode: procedureCode,
            description: description,
            cost: parseFloat(cost) || 0,
            scheduledDate: scheduledDate || null,
            completedAt: isCompleted && completedAt ? completedAt : null,
          });
        }
      });

      const data = {
        appointmentReportID: formData.get("appointmentReportID"),
        status: formData.get("status"),
        dentistNotes: formData.get("dentistNotes"),
        items: items,
      };

      try {
        const response = await fetch(
          `${window.BASE_URL}/doctor/create-treatment-plan`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
          },
        );

        const result = await response.json();

        if (result.success) {
          if (window.toast && typeof window.toast.success === "function") {
            window.toast.success(
              result.message || "Treatment plan created successfully",
            );
          }
          cancelTreatmentPlan();
        } else {
          if (window.toast && typeof window.toast.error === "function") {
            window.toast.error(
              result.message || "Failed to create treatment plan",
            );
          }
        }
      } catch (error) {
        console.error("Error creating treatment plan:", error);
        if (window.toast && typeof window.toast.error === "function") {
          window.toast.error(
            "Network error occurred while creating treatment plan",
          );
        }
      }
    });
  }
});
