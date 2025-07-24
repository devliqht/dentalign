document.addEventListener("DOMContentLoaded", function () {
  let patientId = null;
  let dentalChart = null;
  let teethData = {};
  let currentToothNumber = null;
  let hasUnsavedChanges = false;

  let treatmentItemCounter = 0;
  let validAppointmentReports = [];

  const pathParts = window.location.pathname.split("/");
  patientId = pathParts[pathParts.length - 1];

  if (!patientId || isNaN(patientId)) {
    console.error("Invalid patient ID in URL");
    return;
  }

  loadPatientInfo();
  loadDentalChart();
  loadTreatmentPlans();
  loadValidAppointmentReports();
  initializeEventListeners();

  function loadPatientInfo() {
    fetch(
      `${window.BASE_URL}/doctor/get-patient-details?patient_id=${patientId}`,
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          updatePatientInfo(data.patient);
        } else {
          console.error("Failed to load patient info:", data.message);
        }
      })
      .catch((error) => {
        console.error("Error loading patient info:", error);
      });
  }

  function updatePatientInfo(patient) {
    document.getElementById("patient-name").textContent =
      `${patient.FirstName} ${patient.LastName}`;
    document.getElementById("patient-id-display").textContent =
      patient.PatientID;

    const patientInfoDiv = document.getElementById("patient-info");
    patientInfoDiv.innerHTML = `
            <div>
                <h3 class="text-lg font-semibold text-gray-900">${patient.FirstName} ${patient.LastName}</h3>
                <p class="text-gray-600">${patient.Email}</p>
            </div>
            <div class="text-sm text-gray-500">
                Patient ID: #${patient.PatientID}
            </div>
        `;
  }

  function loadDentalChart() {
    fetch(`${window.BASE_URL}/doctor/get-dental-chart?patient_id=${patientId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          dentalChart = data.dentalChart;
          teethData = data.teethData;
          updateToothChart();
        } else {
          console.error("Failed to load dental chart:", data.message);
        }
      })
      .catch((error) => {
        console.error("Error loading dental chart:", error);
      });
  }

  function updateToothChart() {
    const toothContainers = document.querySelectorAll(".tooth-container");
    toothContainers.forEach((container) => {
      const toothNumber = container.dataset.tooth;
      const toothElement = container.querySelector(".tooth");
      const toothData = teethData[toothNumber];

      if (toothData && toothData.Status) {
        let statusColor = "bg-gray-200";
        switch (toothData.Status.toLowerCase()) {
          case "healthy":
          case "good":
            statusColor = "bg-green-500";
            break;
          case "watch":
          case "monitoring":
            statusColor = "bg-yellow-500";
            break;
          case "treatment needed":
          case "cavity":
          case "issue":
            statusColor = "bg-red-500";
            break;
          default:
            statusColor = "bg-blue-500";
        }

        toothElement.className = toothElement.className.replace(
          /bg-\w+-\d+/g,
          "",
        );
        toothElement.classList.add(statusColor);
      }

      container.dataset.status = toothData?.Status || "";
      container.dataset.notes = toothData?.Notes || "";
    });
  }

  function initializeEventListeners() {
    const toothContainers = document.querySelectorAll(".tooth-container");
    toothContainers.forEach((container) => {
      container.addEventListener("click", function () {
        openToothEditModal(this.dataset.tooth);
      });
    });

    document
      .getElementById("create-treatment-plan-btn")
      .addEventListener("click", showTreatmentPlanForm);
    document
      .getElementById("cancel-treatment-plan-btn")
      .addEventListener("click", hideTreatmentPlanForm);
    document
      .getElementById("save-treatment-plan-btn")
      .addEventListener("click", saveTreatmentPlan);
    document
      .getElementById("add-treatment-item-btn")
      .addEventListener("click", addTreatmentItem);

    window.addEventListener("beforeunload", function (e) {
      if (hasUnsavedChanges) {
        e.preventDefault();
        e.returnValue =
          "You have unsaved changes. Are you sure you want to leave?";
      }
    });
  }

  function loadTreatmentPlans() {
    fetch(
      `${window.BASE_URL}/doctor/get-patient-treatment-plan?patient_id=${patientId}`,
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          displayTreatmentPlans(data.treatmentPlans || []);
        } else {
          console.error("Failed to load treatment plans:", data.message);
          displayTreatmentPlans([]);
        }
      })
      .catch((error) => {
        console.error("Error loading treatment plans:", error);
        displayTreatmentPlans([]);
      });
  }

  function displayTreatmentPlans(treatmentPlans) {
    const container = document.getElementById("treatment-plans-list");

    if (treatmentPlans.length === 0) {
      container.innerHTML = `
                <div class="text-gray-500 text-center py-4 text-sm">
                    No treatment plans found for this patient
                </div>
            `;
      return;
    }

    container.innerHTML = treatmentPlans
      .map(
        (plan) => `
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h5 class="font-semibold text-gray-900">Treatment Plan #${plan.TreatmentPlanID}</h5>
                        <p class="text-sm text-gray-600">${plan.DentistNotes || "No notes provided"}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${getStatusColor(plan.Status)}">
                            ${plan.Status}
                        </span>
                        <button onclick="viewTreatmentPlan(${plan.TreatmentPlanID})"
                                class="glass-card bg-nhd-blue/80 hover:bg-nhd-blue shadow-md text-sm font-medium">
                            View Details
                        </button>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Created: ${new Date(plan.AssignedAt).toLocaleDateString()}
                    ${plan.progress !== undefined ? `• Progress: ${plan.progress}%` : ""}
                </div>
            </div>
        `,
      )
      .join("");
  }

  function getStatusColor(status) {
    switch (status.toLowerCase()) {
      case "completed":
        return "bg-green-100 text-green-800";
      case "in_progress":
        return "bg-blue-100 text-blue-800";
      case "pending":
        return "bg-yellow-100 text-yellow-800";
      case "cancelled":
        return "bg-red-100 text-red-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  }

  function loadValidAppointmentReports() {
    fetch(
      `${window.BASE_URL}/doctor/get-valid-appointment-reports?patient_id=${patientId}`,
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          validAppointmentReports = data.reports || [];
          populateAppointmentReportDropdown();
        } else {
          console.error(
            "Failed to load valid appointment reports:",
            data.message,
          );
        }
      })
      .catch((error) => {
        console.error("Error loading valid appointment reports:", error);
      });
  }

  function populateAppointmentReportDropdown() {
    const select = document.getElementById("appointment-report-select");
    select.innerHTML =
      '<option value="">Select an appointment report...</option>';

    validAppointmentReports.forEach((report) => {
      const option = document.createElement("option");
      option.value = report.AppointmentReportID;
      option.textContent = `${new Date(report.AppointmentDate).toLocaleDateString()} - ${report.AppointmentType} (${report.DoctorName})`;
      select.appendChild(option);
    });
  }

  function showTreatmentPlanForm() {
    document.getElementById("treatment-plan-form").classList.remove("hidden");
    document
      .getElementById("create-treatment-plan-btn")
      .classList.add("hidden");

    document.getElementById("appointment-report-select").value = "";
    document.getElementById("treatment-plan-notes").value = "";
    document.getElementById("treatment-plan-status").value = "pending";
    document.getElementById("treatment-items-container").innerHTML = "";
    treatmentItemCounter = 0;

    addTreatmentItem();
  }

  function hideTreatmentPlanForm() {
    document.getElementById("treatment-plan-form").classList.add("hidden");
    document
      .getElementById("create-treatment-plan-btn")
      .classList.remove("hidden");
  }

  function addTreatmentItem() {
    treatmentItemCounter++;
    const container = document.getElementById("treatment-items-container");

    const itemHtml = `
            <div class="treatment-item border border-gray-200 rounded-lg p-3" data-item-id="${treatmentItemCounter}">
                <div class="flex justify-between items-start mb-3">
                    <h6 class="font-medium text-gray-700">Treatment Item #${treatmentItemCounter}</h6>
                    <button type="button" onclick="removeTreatmentItem(${treatmentItemCounter})"
                            class="text-red-600 hover:text-red-800 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tooth Number (Optional)</label>
                        <select class="tooth-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent text-sm">
                            <option value="">No specific tooth</option>
                            ${generateToothOptions()}
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Procedure Code</label>
                        <select class="procedure-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent text-sm">
                            <option value="">Select procedure...</option>
                            ${generateProcedureOptions()}
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" class="description-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent text-sm"
                               placeholder="Enter treatment description...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Cost (₱)</label>
                        <input type="number" step="0.01" min="0" class="cost-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent text-sm"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date (Optional)</label>
                        <input type="date" class="scheduled-date-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent text-sm">
                    </div>
                </div>

                <div class="mt-3 flex items-center">
                    <input type="checkbox" class="completed-checkbox mr-2" id="completed-${treatmentItemCounter}">
                    <label for="completed-${treatmentItemCounter}" class="text-sm text-gray-700">Mark as completed</label>
                    <div class="completed-date-container ml-4 hidden">
                        <input type="datetime-local" class="completed-date-input px-2 py-1 border border-gray-300 rounded text-sm">
                    </div>
                </div>
            </div>
        `;

    container.insertAdjacentHTML("beforeend", itemHtml);

    const newItem = container.lastElementChild;
    const checkbox = newItem.querySelector(".completed-checkbox");
    const completedDateContainer = newItem.querySelector(
      ".completed-date-container",
    );

    checkbox.addEventListener("change", function () {
      if (this.checked) {
        completedDateContainer.classList.remove("hidden");
        completedDateContainer.querySelector(".completed-date-input").value =
          new Date().toISOString().slice(0, 16);
      } else {
        completedDateContainer.classList.add("hidden");
        completedDateContainer.querySelector(".completed-date-input").value =
          "";
      }
    });

    const procedureSelect = newItem.querySelector(".procedure-select");
    const descriptionInput = newItem.querySelector(".description-input");

    procedureSelect.addEventListener("change", function () {
      if (this.value && !descriptionInput.value) {
        const selectedOption = this.options[this.selectedIndex];
        descriptionInput.value =
          selectedOption.textContent.split(" - ")[1] ||
          selectedOption.textContent;
      }
    });
  }

  function generateToothOptions() {
    const teeth = [
      { num: 1, name: "Upper Right Third Molar" },
      { num: 2, name: "Upper Right Second Molar" },
      { num: 3, name: "Upper Right First Molar" },
      { num: 4, name: "Upper Right Second Premolar" },
      { num: 5, name: "Upper Right First Premolar" },
      { num: 6, name: "Upper Right Canine" },
      { num: 7, name: "Upper Right Lateral Incisor" },
      { num: 8, name: "Upper Right Central Incisor" },
      { num: 9, name: "Upper Left Central Incisor" },
      { num: 10, name: "Upper Left Lateral Incisor" },
      { num: 11, name: "Upper Left Canine" },
      { num: 12, name: "Upper Left First Premolar" },
      { num: 13, name: "Upper Left Second Premolar" },
      { num: 14, name: "Upper Left First Molar" },
      { num: 15, name: "Upper Left Second Molar" },
      { num: 16, name: "Upper Left Third Molar" },
      { num: 17, name: "Lower Left Third Molar" },
      { num: 18, name: "Lower Left Second Molar" },
      { num: 19, name: "Lower Left First Molar" },
      { num: 20, name: "Lower Left Second Premolar" },
      { num: 21, name: "Lower Left First Premolar" },
      { num: 22, name: "Lower Left Canine" },
      { num: 23, name: "Lower Left Lateral Incisor" },
      { num: 24, name: "Lower Left Central Incisor" },
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
          `<option value="${tooth.num}">${tooth.num} - ${tooth.name}</option>`,
      )
      .join("");
  }

  function generateProcedureOptions() {
    const procedures = [
      { code: "D0150", desc: "Comprehensive oral evaluation" },
      { code: "D0210", desc: "Intraoral complete series radiographic images" },
      { code: "D1110", desc: "Prophylaxis - adult" },
      { code: "D1208", desc: "Topical application of fluoride" },
      { code: "D2140", desc: "Amalgam - one surface, primary or permanent" },
      { code: "D2150", desc: "Amalgam - two surfaces, primary or permanent" },
      { code: "D2160", desc: "Amalgam - three surfaces, primary or permanent" },
      {
        code: "D2161",
        desc: "Amalgam - four or more surfaces, primary or permanent",
      },
      { code: "D2330", desc: "Resin-based composite - one surface, anterior" },
      { code: "D2331", desc: "Resin-based composite - two surfaces, anterior" },
      {
        code: "D2332",
        desc: "Resin-based composite - three surfaces, anterior",
      },
      { code: "D2391", desc: "Resin-based composite - one surface, posterior" },
      {
        code: "D2392",
        desc: "Resin-based composite - two surfaces, posterior",
      },
      {
        code: "D2393",
        desc: "Resin-based composite - three surfaces, posterior",
      },
      {
        code: "D2394",
        desc: "Resin-based composite - four or more surfaces, posterior",
      },
      { code: "D2740", desc: "Crown - porcelain/ceramic substrate" },
      { code: "D2750", desc: "Crown - porcelain fused to high noble metal" },
      { code: "D2790", desc: "Crown - full cast high noble metal" },
      {
        code: "D3110",
        desc: "Pulp cap - direct (excluding final restoration)",
      },
      {
        code: "D3220",
        desc: "Therapeutic pulpotomy (excluding final restoration)",
      },
      { code: "D3310", desc: "Endodontic therapy, anterior tooth" },
      { code: "D3320", desc: "Endodontic therapy, premolar tooth" },
      { code: "D3330", desc: "Endodontic therapy, molar tooth" },
      {
        code: "D4210",
        desc: "Gingivectomy or gingivoplasty - four or more contiguous teeth",
      },
      {
        code: "D4341",
        desc: "Periodontal scaling and root planing - four or more teeth per quadrant",
      },
      { code: "D5110", desc: "Complete denture - maxillary" },
      { code: "D5120", desc: "Complete denture - mandibular" },
      {
        code: "D5213",
        desc: "Maxillary partial denture - cast metal framework with resin denture teeth",
      },
      {
        code: "D5214",
        desc: "Mandibular partial denture - cast metal framework with resin denture teeth",
      },
      {
        code: "D6010",
        desc: "Surgical placement of implant body: endosteal implant",
      },
      { code: "D6040", desc: "Surgical placement: eposteal implant" },
      { code: "D6050", desc: "Surgical placement: transosteal implant" },
      { code: "D6240", desc: "Pontic - porcelain fused to high noble metal" },
      { code: "D7110", desc: "Extraction, erupted tooth or exposed root" },
      {
        code: "D7140",
        desc: "Extraction, erupted tooth requiring removal of bone and/or sectioning",
      },
      {
        code: "D7210",
        desc: "Extraction, erupted tooth requiring removal of bone and/or sectioning of tooth, and including elevation of mucoperiosteal flap if indicated",
      },
      {
        code: "D8080",
        desc: "Comprehensive orthodontic treatment of the transitional dentition",
      },
      {
        code: "D8090",
        desc: "Comprehensive orthodontic treatment of the adolescent dentition",
      },
      {
        code: "D9110",
        desc: "Palliative (emergency) treatment of dental pain - minor procedure",
      },
      { code: "D9940", desc: "Occlusal guard, by report" },
    ];

    return procedures
      .map(
        (proc) =>
          `<option value="${proc.code}">${proc.code} - ${proc.desc}</option>`,
      )
      .join("");
  }

  window.removeTreatmentItem = function (itemId) {
    const item = document.querySelector(`[data-item-id="${itemId}"]`);
    if (item) {
      item.remove();
    }
  };

  window.viewTreatmentPlan = function (treatmentPlanId) {
    openTreatmentPlanModal(treatmentPlanId);
  };

  window.toggleItemCompletion = function (treatmentItemId, markComplete) {
    const endpoint = markComplete
      ? "mark-treatment-item-complete"
      : "mark-treatment-item-incomplete";
    const data = { treatmentItemID: treatmentItemId };

    if (markComplete) {
      data.completedAt = new Date()
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
    }

    fetch(`${window.BASE_URL}/doctor/${endpoint}`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.toast.success(data.message);
          openTreatmentPlanModal(currentTreatmentPlan.TreatmentPlanID);
        } else {
          window.toast.error(data.message || "Failed to update item status");
        }
      })
      .catch((error) => {
        console.error("Error updating item status:", error);
        window.toast.error("An error occurred while updating the item status");
      });
  };

  window.editTreatmentItem = function (treatmentItemId) {
    currentEditingItem = currentTreatmentItems.find(
      (item) => item.TreatmentItemID == treatmentItemId,
    );
    if (!currentEditingItem) {
      window.toast.error("Treatment item not found");
      return;
    }

    document.getElementById("edit-treatment-item-id").value =
      currentEditingItem.TreatmentItemID;
    document.getElementById("edit-item-description").value =
      currentEditingItem.Description || "";
    document.getElementById("edit-item-cost").value =
      currentEditingItem.Cost || "";
    document.getElementById("edit-item-scheduled-date").value =
      currentEditingItem.ScheduledDate || "";

    populateToothSelect("edit-item-tooth-number");
    document.getElementById("edit-item-tooth-number").value =
      currentEditingItem.ToothNumber || "";

    populateProcedureSelect("edit-item-procedure-code");
    document.getElementById("edit-item-procedure-code").value =
      currentEditingItem.ProcedureCode || "";

    const completedCheckbox = document.getElementById("edit-item-completed");
    const completedDateContainer = document.getElementById(
      "edit-item-completed-date-container",
    );
    const completedDateInput = document.getElementById(
      "edit-item-completed-date",
    );

    if (currentEditingItem.CompletedAt) {
      completedCheckbox.checked = true;
      completedDateContainer.classList.remove("hidden");
      const completedDate = new Date(currentEditingItem.CompletedAt);
      completedDateInput.value = completedDate.toISOString().slice(0, 16);
    } else {
      completedCheckbox.checked = false;
      completedDateContainer.classList.add("hidden");
      completedDateInput.value = "";
    }

    document
      .getElementById("treatment-item-edit-modal")
      .classList.remove("hidden");
  };

  window.deleteTreatmentItemConfirm = function (treatmentItemId) {
    if (
      confirm(
        "Are you sure you want to delete this treatment item? This action cannot be undone.",
      )
    ) {
      fetch(`${window.BASE_URL}/doctor/delete-treatment-plan-item`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ treatmentItemID: treatmentItemId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            window.toast.success("Treatment item deleted successfully");
            openTreatmentPlanModal(currentTreatmentPlan.TreatmentPlanID);
          } else {
            window.toast.error(
              data.message || "Failed to delete treatment item",
            );
          }
        })
        .catch((error) => {
          console.error("Error deleting treatment item:", error);
          window.toast.error(
            "An error occurred while deleting the treatment item",
          );
        });
    }
  };

  function saveTreatmentPlan() {
    if (!confirm("Are you sure you want to create this treatment plan?")) {
      return;
    }

    const appointmentReportID = document.getElementById(
      "appointment-report-select",
    ).value;
    const notes = document.getElementById("treatment-plan-notes").value;
    const status = document.getElementById("treatment-plan-status").value;

    if (!appointmentReportID) {
      window.toast.error("Please select an appointment report");
      return;
    }

    const items = [];
    const treatmentItems = document.querySelectorAll(".treatment-item");

    treatmentItems.forEach((item) => {
      const toothNumber = item.querySelector(".tooth-select").value || null;
      const procedureCode = item.querySelector(".procedure-select").value || "";
      const description = item.querySelector(".description-input").value;
      const cost = parseFloat(item.querySelector(".cost-input").value) || 0;
      const scheduledDate =
        item.querySelector(".scheduled-date-input").value || null;
      const isCompleted = item.querySelector(".completed-checkbox").checked;
      const completedAt = isCompleted
        ? item.querySelector(".completed-date-input").value
        : null;

      if (description.trim()) {
        items.push({
          toothNumber,
          procedureCode,
          description: description.trim(),
          cost,
          scheduledDate,
          completedAt,
        });
      }
    });

    if (items.length === 0) {
      window.toast.error("Please add at least one treatment item");
      return;
    }

    const treatmentPlanData = {
      appointmentReportID: parseInt(appointmentReportID),
      status,
      dentistNotes: notes.trim(),
      items,
    };

    fetch(`${window.BASE_URL}/doctor/create-treatment-plan`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(treatmentPlanData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.toast.success("Treatment plan created successfully");
          hideTreatmentPlanForm();
          loadTreatmentPlans();
        } else {
          window.toast.error(data.message || "Failed to create treatment plan");
        }
      })
      .catch((error) => {
        console.error("Error creating treatment plan:", error);
        window.toast.error(
          "An error occurred while creating the treatment plan",
        );
      });
  }

  function openToothEditModal(toothNumber) {
    currentToothNumber = toothNumber;
    const toothData = teethData[toothNumber] || {};

    document.getElementById("edit-tooth-number").textContent = toothNumber;
    document.getElementById("edit-tooth-name").textContent =
      getToothName(toothNumber);
    document.getElementById("edit-tooth-number-input").value = toothNumber;
    document.getElementById("edit-tooth-notes").value = toothData.Notes || "";

    const statusRadios = document.querySelectorAll('input[name="status"]');
    statusRadios.forEach((radio) => {
      radio.checked = radio.value === (toothData.Status || "");
      if (radio.checked) {
        radio
          .closest("label")
          .classList.add("ring-2", "ring-blue-500", "bg-blue-50");
      } else {
        radio
          .closest("label")
          .classList.remove("ring-2", "ring-blue-500", "bg-blue-50");
      }
    });

    statusRadios.forEach((radio) => {
      radio.addEventListener("change", function () {
        statusRadios.forEach((r) => {
          r.closest("label").classList.remove(
            "ring-2",
            "ring-blue-500",
            "bg-blue-50",
          );
        });
        if (this.checked) {
          this.closest("label").classList.add(
            "ring-2",
            "ring-blue-500",
            "bg-blue-50",
          );
        }
        updateStatusPreview(this.value);
      });
    });

    document.getElementById("tooth-edit-modal").classList.remove("hidden");
  }

  function getToothName(toothNumber) {
    const names = {
      1: "Upper Right Third Molar",
      2: "Upper Right Second Molar",
      3: "Upper Right First Molar",
      4: "Upper Right Second Premolar",
      5: "Upper Right First Premolar",
      6: "Upper Right Canine",
      7: "Upper Right Lateral Incisor",
      8: "Upper Right Central Incisor",
      9: "Upper Left Central Incisor",
      10: "Upper Left Lateral Incisor",
      11: "Upper Left Canine",
      12: "Upper Left First Premolar",
      13: "Upper Left Second Premolar",
      14: "Upper Left First Molar",
      15: "Upper Left Second Molar",
      16: "Upper Left Third Molar",
      17: "Lower Left Third Molar",
      18: "Lower Left Second Molar",
      19: "Lower Left First Molar",
      20: "Lower Left Second Premolar",
      21: "Lower Left First Premolar",
      22: "Lower Left Canine",
      23: "Lower Left Lateral Incisor",
      24: "Lower Left Central Incisor",
      25: "Lower Right Central Incisor",
      26: "Lower Right Lateral Incisor",
      27: "Lower Right Canine",
      28: "Lower Right First Premolar",
      29: "Lower Right Second Premolar",
      30: "Lower Right First Molar",
      31: "Lower Right Second Molar",
      32: "Lower Right Third Molar",
    };
    return names[toothNumber] || `Tooth #${toothNumber}`;
  }

  window.closeToothEditModal = function () {
    document.getElementById("tooth-edit-modal").classList.add("hidden");
    currentToothNumber = null;
  };

  window.updateStatusPreview = function (status) {
    console.log("Status updated to:", status);
  };

  window.addCondition = function (condition) {
    const notesTextarea = document.getElementById("edit-tooth-notes");
    const currentNotes = notesTextarea.value;

    if (
      currentNotes &&
      !currentNotes.endsWith("\n") &&
      !currentNotes.endsWith(" ")
    ) {
      notesTextarea.value += "; ";
    }
    notesTextarea.value += condition;
    notesTextarea.focus();
  };

  window.saveToothData = function () {
    if (!currentToothNumber) return;

    if (!confirm("Are you sure you want to save changes to this tooth data?")) {
      return;
    }

    const status =
      document.querySelector('input[name="status"]:checked')?.value || "";
    const notes = document.getElementById("edit-tooth-notes").value;

    const toothData = {
      patientId: parseInt(patientId),
      toothNumber: parseInt(currentToothNumber),
      status: status,
      notes: notes,
    };

    fetch(`${window.BASE_URL}/doctor/update-dental-chart-item`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(toothData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Update local data
          teethData[currentToothNumber] = {
            ToothNumber: currentToothNumber,
            Status: status,
            Notes: notes,
          };

          // Update visual representation
          updateToothChart();

          window.toast.success("Tooth data saved successfully");
          closeToothEditModal();
          hasUnsavedChanges = false;
        } else {
          window.toast.error(data.message || "Failed to save tooth data");
        }
      })
      .catch((error) => {
        console.error("Error saving tooth data:", error);
        window.toast.error("An error occurred while saving");
      });
  };

  window.markAllTeethAs = function (status) {
    if (confirm(`Are you sure you want to mark all teeth as "${status}"?`)) {
      const promises = [];

      for (let i = 1; i <= 32; i++) {
        const toothData = {
          patientId: parseInt(patientId),
          toothNumber: i,
          status: status,
          notes: teethData[i]?.Notes || "",
        };

        promises.push(
          fetch(`${window.BASE_URL}/doctor/update-dental-chart-item`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(toothData),
          }),
        );
      }

      Promise.all(promises)
        .then((responses) => Promise.all(responses.map((r) => r.json())))
        .then((results) => {
          const successful = results.filter((r) => r.success).length;
          window.toast.success(`Updated ${successful} teeth successfully`);
          loadDentalChart(); // Reload to get fresh data
        })
        .catch((error) => {
          console.error("Error bulk updating teeth:", error);
          window.toast.error("An error occurred during bulk update");
        });
    }
  };

  window.clearAllNotes = function () {
    if (confirm("Are you sure you want to clear all notes for all teeth?")) {
      const promises = [];

      for (let i = 1; i <= 32; i++) {
        const toothData = {
          patientId: parseInt(patientId),
          toothNumber: i,
          status: teethData[i]?.Status || "",
          notes: "",
        };

        promises.push(
          fetch(`${window.BASE_URL}/doctor/update-dental-chart-item`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(toothData),
          }),
        );
      }

      Promise.all(promises)
        .then((responses) => Promise.all(responses.map((r) => r.json())))
        .then((results) => {
          const successful = results.filter((r) => r.success).length;
          window.toast.success(`Cleared notes for ${successful} teeth`);
          loadDentalChart(); // Reload to get fresh data
        })
        .catch((error) => {
          console.error("Error clearing notes:", error);
          window.toast.error("An error occurred while clearing notes");
        });
    }
  };

  window.saveAllChanges = function () {
    window.toast.info("All changes saved automatically");
  };

  window.generateReport = function () {
    window.open(
      `${window.BASE_URL}/doctor/dental-chart/report/${patientId}`,
      "_blank",
    );
  };

  // ===== TREATMENT PLAN MODAL FUNCTIONS =====
  let currentTreatmentPlan = null;
  let currentTreatmentItems = [];
  let currentEditingItem = null;

  function openTreatmentPlanModal(treatmentPlanId) {
    fetch(
      `${window.BASE_URL}/doctor/get-treatment-plan?treatment_plan_id=${treatmentPlanId}`,
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          currentTreatmentPlan = data.treatmentPlan;
          currentTreatmentItems = data.items || [];
          populateTreatmentPlanModal();
          document
            .getElementById("treatment-plan-edit-modal")
            .classList.remove("hidden");
        } else {
          window.toast.error(data.message || "Failed to load treatment plan");
        }
      })
      .catch((error) => {
        console.error("Error loading treatment plan:", error);
        window.toast.error(
          "An error occurred while loading the treatment plan",
        );
      });
  }

  function populateTreatmentPlanModal() {
    if (!currentTreatmentPlan) return;

    document.getElementById("treatment-plan-id-display").textContent =
      currentTreatmentPlan.TreatmentPlanID;
    document.getElementById("treatment-plan-patient-name").textContent =
      currentTreatmentPlan.PatientName || "Unknown Patient";

    document.getElementById("treatment-plan-status-edit").value =
      currentTreatmentPlan.Status || "pending";
    document.getElementById("treatment-plan-notes-edit").value =
      currentTreatmentPlan.DentistNotes || "";
    document.getElementById("treatment-plan-created-date").textContent =
      new Date(currentTreatmentPlan.AssignedAt).toLocaleDateString();

    const completedItems = currentTreatmentItems.filter(
      (item) => item.CompletedAt,
    ).length;
    const totalItems = currentTreatmentItems.length;
    const progress = totalItems > 0 ? (completedItems / totalItems) * 100 : 0;

    document.getElementById("treatment-plan-progress-bar").style.width =
      `${progress}%`;
    document.getElementById("treatment-plan-progress-text").textContent =
      `${Math.round(progress)}%`;

    displayTreatmentItems();
  }

  function displayTreatmentItems() {
    const container = document.getElementById("treatment-items-list");

    if (currentTreatmentItems.length === 0) {
      container.innerHTML = `
                <div class="text-gray-500 text-center py-8">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p>No treatment items found</p>
                    <p class="text-sm">Click "Add Item" to create the first treatment item</p>
                </div>
            `;
      return;
    }

    container.innerHTML = currentTreatmentItems
      .map(
        (item) => `
            <div class="border border-gray-200 bg-white/80 rounded-lg p-4 shadow-sm transition-shadow">
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h4 class="font-medium text-gray-900">${item.Description || "No description"}</h4>
                            ${
                              item.CompletedAt
                                ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Completed</span>'
                                : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>'
                            }
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Tooth:</span>
                                ${item.ToothNumber ? `#${item.ToothNumber}` : "General"}
                            </div>
                            <div>
                                <span class="font-medium">Procedure:</span>
                                ${item.ProcedureCode || "N/A"}
                            </div>
                            <div>
                                <span class="font-medium">Cost:</span>
                                ₱${parseFloat(item.Cost || 0).toFixed(2)}
                            </div>
                            <div>
                                <span class="font-medium">Scheduled:</span>
                                ${item.ScheduledDate ? new Date(item.ScheduledDate).toLocaleDateString() : "TBD"}
                            </div>
                        </div>

                        ${
                          item.CompletedAt
                            ? `
                            <div class="mt-2 text-xs text-green-600">
                                Completed: ${new Date(item.CompletedAt).toLocaleString()}
                            </div>
                        `
                            : ""
                        }
                    </div>

                    <div class="flex items-center space-x-2 ml-4">
                        ${
                          !item.CompletedAt
                            ? `
                            <button onclick="toggleItemCompletion('${item.TreatmentItemID}', true)"
                                    class="glass-card bg-green-600/80 hover:bg-green-600 text-sm font-medium"
                                    title="Mark as completed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        `
                            : `
                            <button onclick="toggleItemCompletion('${item.TreatmentItemID}', false)"
                                    class="glass-card bg-yellow-600/80 hover:bg-yellow-700 text-sm font-medium"
                                    title="Mark as incomplete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        `
                        }

                        <button onclick="editTreatmentItem('${item.TreatmentItemID}')"
                                class="glass-card bg-nhd-blue/80 hover:bg-nhd-blue text-sm font-medium">
                            Edit
                        </button>

                        <button onclick="deleteTreatmentItemConfirm('${item.TreatmentItemID}')"
                                class="glass-card bg-red-600/80 hover:bg-red-800 text-sm font-medium">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        `,
      )
      .join("");
  }

  function addNewTreatmentItem() {
    currentEditingItem = null;

    document.getElementById("edit-treatment-item-id").value = "";
    document.getElementById("edit-item-description").value = "";
    document.getElementById("edit-item-cost").value = "";
    document.getElementById("edit-item-scheduled-date").value = "";

    populateToothSelect("edit-item-tooth-number");
    populateProcedureSelect("edit-item-procedure-code");

    document.getElementById("edit-item-completed").checked = false;
    document
      .getElementById("edit-item-completed-date-container")
      .classList.add("hidden");
    document.getElementById("edit-item-completed-date").value = "";

    document
      .getElementById("treatment-item-edit-modal")
      .classList.remove("hidden");
  }

  function populateToothSelect(selectId) {
    const select = document.getElementById(selectId);
    select.innerHTML =
      '<option value="">No specific tooth</option>' + generateToothOptions();
  }

  function populateProcedureSelect(selectId) {
    const select = document.getElementById(selectId);
    select.innerHTML =
      '<option value="">Select procedure...</option>' +
      generateProcedureOptions();
  }

  window.closeTreatmentPlanModal = function () {
    document
      .getElementById("treatment-plan-edit-modal")
      .classList.add("hidden");
    currentTreatmentPlan = null;
    currentTreatmentItems = [];
  };

  window.closeTreatmentItemModal = function () {
    document
      .getElementById("treatment-item-edit-modal")
      .classList.add("hidden");
    currentEditingItem = null;
  };

  window.saveTreatmentItem = function () {
    const treatmentItemId = document.getElementById(
      "edit-treatment-item-id",
    ).value;
    const isUpdate = !!treatmentItemId;
    const confirmMessage = isUpdate
      ? "Are you sure you want to save changes to this treatment item?"
      : "Are you sure you want to create this treatment item?";

    if (!confirm(confirmMessage)) {
      return;
    }

    const description = document
      .getElementById("edit-item-description")
      .value.trim();
    const cost =
      parseFloat(document.getElementById("edit-item-cost").value) || 0;
    const scheduledDate =
      document.getElementById("edit-item-scheduled-date").value || null;
    const toothNumber =
      document.getElementById("edit-item-tooth-number").value || null;
    const procedureCode =
      document.getElementById("edit-item-procedure-code").value || "";
    const isCompleted = document.getElementById("edit-item-completed").checked;
    const completedAt = isCompleted
      ? document.getElementById("edit-item-completed-date").value
      : null;

    if (!description) {
      window.toast.error("Description is required");
      return;
    }

    const itemData = {
      toothNumber,
      procedureCode,
      description,
      cost,
      scheduledDate,
      completedAt: completedAt ? completedAt.replace("T", " ") + ":00" : null,
    };

    let endpoint, method;
    if (treatmentItemId) {
      endpoint = "update-treatment-plan-item";
      itemData.treatmentItemID = treatmentItemId;
    } else {
      endpoint = "add-treatment-plan-item";
      itemData.treatmentPlanID = currentTreatmentPlan.TreatmentPlanID;
    }

    fetch(`${window.BASE_URL}/doctor/${endpoint}`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(itemData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.toast.success(data.message);
          closeTreatmentItemModal();
          openTreatmentPlanModal(currentTreatmentPlan.TreatmentPlanID);
        } else {
          window.toast.error(data.message || "Failed to save treatment item");
        }
      })
      .catch((error) => {
        console.error("Error saving treatment item:", error);
        window.toast.error("An error occurred while saving the treatment item");
      });
  };

  window.deleteTreatmentItem = function () {
    const treatmentItemId = document.getElementById(
      "edit-treatment-item-id",
    ).value;
    if (!treatmentItemId) {
      window.toast.error("No item to delete");
      return;
    }

    if (
      confirm(
        "Are you sure you want to delete this treatment item? This action cannot be undone.",
      )
    ) {
      fetch(`${window.BASE_URL}/doctor/delete-treatment-plan-item`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ treatmentItemID: treatmentItemId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            window.toast.success("Treatment item deleted successfully");
            closeTreatmentItemModal();
            openTreatmentPlanModal(currentTreatmentPlan.TreatmentPlanID);
          } else {
            window.toast.error(
              data.message || "Failed to delete treatment item",
            );
          }
        })
        .catch((error) => {
          console.error("Error deleting treatment item:", error);
          window.toast.error(
            "An error occurred while deleting the treatment item",
          );
        });
    }
  };

  document
    .getElementById("add-treatment-item-modal-btn")
    .addEventListener("click", addNewTreatmentItem);

  document
    .getElementById("save-treatment-plan-changes-btn")
    .addEventListener("click", function () {
      if (
        !confirm(
          "Are you sure you want to save all changes to this treatment plan?",
        )
      ) {
        return;
      }

      const status = document.getElementById(
        "treatment-plan-status-edit",
      ).value;
      const notes = document
        .getElementById("treatment-plan-notes-edit")
        .value.trim();

      const planData = {
        treatmentPlanID: currentTreatmentPlan.TreatmentPlanID,
        status,
        dentistNotes: notes,
      };

      fetch(`${window.BASE_URL}/doctor/update-treatment-plan`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(planData),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            window.toast.success("Treatment plan updated successfully");
            currentTreatmentPlan.Status = status;
            currentTreatmentPlan.DentistNotes = notes;
            loadTreatmentPlans(); // Refresh the list in the main page
          } else {
            window.toast.error(
              data.message || "Failed to update treatment plan",
            );
          }
        })
        .catch((error) => {
          console.error("Error updating treatment plan:", error);
          window.toast.error(
            "An error occurred while updating the treatment plan",
          );
        });
    });

  document
    .getElementById("delete-treatment-plan-btn")
    .addEventListener("click", function () {
      if (
        confirm(
          "Are you sure you want to cancel this entire treatment plan? This will set the plan status to 'cancelled' but preserve all treatment data.",
        )
      ) {
        fetch(`${window.BASE_URL}/doctor/delete-treatment-plan`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            treatmentPlanID: currentTreatmentPlan.TreatmentPlanID,
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              window.toast.success("Treatment plan cancelled successfully");
              closeTreatmentPlanModal();
              loadTreatmentPlans();
            } else {
              window.toast.error(
                data.message || "Failed to cancel treatment plan",
              );
            }
          })
          .catch((error) => {
            console.error("Error cancelling treatment plan:", error);
            window.toast.error(
              "An error occurred while cancelling the treatment plan",
            );
          });
      }
    });

  document
    .getElementById("edit-item-completed")
    .addEventListener("change", function () {
      const container = document.getElementById(
        "edit-item-completed-date-container",
      );
      const dateInput = document.getElementById("edit-item-completed-date");

      if (this.checked) {
        container.classList.remove("hidden");
        if (!dateInput.value) {
          dateInput.value = new Date().toISOString().slice(0, 16);
        }
      } else {
        container.classList.add("hidden");
        dateInput.value = "";
      }
    });
});
