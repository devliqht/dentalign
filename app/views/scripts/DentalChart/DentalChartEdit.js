let currentPatientId = null;
let dentalChartData = {};
let teethData = {};
let currentEditingTooth = null;
let hasUnsavedChanges = false;

document.addEventListener("DOMContentLoaded", function () {
  const path = window.location.pathname;
  const matches = path.match(/\/doctor\/dental-chart-edit\/(\d+)/);
  if (matches) {
    currentPatientId = matches[1];
    loadPatientData();
    loadDentalChartData();
  }

  setupToothClickHandlers();

  window.addEventListener("beforeunload", function (e) {
    if (hasUnsavedChanges) {
      e.preventDefault();
      e.returnValue =
        "You have unsaved changes. Are you sure you want to leave?";
      return e.returnValue;
    }
  });
});

async function loadPatientData() {
  try {
    const response = await fetch(
      `${window.BASE_URL}/doctor/get-patient-details?patient_id=${currentPatientId}`,
    );
    const data = await response.json();

    if (data.success) {
      const patient = data.patient;
      document.getElementById("patient-name").textContent =
        `${patient.FirstName} ${patient.LastName}`;
      document.getElementById("patient-id-display").textContent = String(
        patient.PatientID,
      ).padStart(4, "0");

      document.getElementById("patient-info").innerHTML = `
                <div>
                    <h4 class="text-lg font-semibold text-gray-900">${patient.FirstName} ${patient.LastName}</h4>
                    <p class="text-gray-600">${patient.Email}</p>
                    <p class="text-sm text-gray-500">Registered: ${new Date(patient.CreatedAt).toLocaleDateString()}</p>
                </div>
                <div class="text-sm text-gray-500">
                    Patient ID: #${String(patient.PatientID).padStart(4, "0")}
                </div>
            `;
    }
  } catch (error) {
    console.error("Error loading patient data:", error);
    if (window.toast) {
      toast.error("Failed to load patient data");
    }
  }
}

async function loadDentalChartData() {
  try {
    const response = await fetch(
      `${window.BASE_URL}/doctor/get-dental-chart?patient_id=${currentPatientId}`,
    );
    const data = await response.json();

    if (data.success) {
      dentalChartData = data.dentalChart;
      teethData = data.teethData;
      updateTeethDisplay();
    } else {
      // Create new dental chart if none exists
      dentalChartData = { PatientID: currentPatientId };
      teethData = {};
      updateTeethDisplay();
    }
  } catch (error) {
    console.error("Error loading dental chart data:", error);
    if (window.toast) {
      toast.error("Failed to load dental chart data");
    }
  }
}

function setupToothClickHandlers() {
  document.querySelectorAll(".tooth-container").forEach((container) => {
    container.addEventListener("click", function () {
      const toothNumber = this.dataset.tooth;
      openToothEditModal(toothNumber);
    });
  });
}

function updateTeethDisplay() {
  document.querySelectorAll(".tooth-container").forEach((container) => {
    const toothNumber = container.dataset.tooth;
    const toothElement = container.querySelector(".tooth");
    const toothData = teethData[toothNumber];

    if (toothData && toothData.Status) {
      const status = toothData.Status.toLowerCase();
      toothElement.className =
        "tooth w-8 h-12 border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex justify-center";

      if (parseInt(toothNumber) <= 16) {
        toothElement.classList.add("rounded-t-full", "items-end");
      } else {
        toothElement.classList.add("rounded-b-full", "items-start");
      }

      if (status.includes("healthy") || status.includes("good")) {
        toothElement.classList.add("bg-green-500");
        toothElement.querySelector("span").className =
          "text-xs font-bold text-white mb-1";
      } else if (status.includes("watch") || status.includes("monitoring")) {
        toothElement.classList.add("bg-yellow-500");
        toothElement.querySelector("span").className =
          "text-xs font-bold text-white mb-1";
      } else if (status.includes("treatment") || status.includes("cavity")) {
        toothElement.classList.add("bg-red-500");
        toothElement.querySelector("span").className =
          "text-xs font-bold text-white mb-1";
      } else {
        toothElement.classList.add("bg-blue-500");
        toothElement.querySelector("span").className =
          "text-xs font-bold text-white mb-1";
      }

      if (parseInt(toothNumber) > 16) {
        toothElement.querySelector("span").className =
          "text-xs font-bold text-white mt-1";
      }
    } else {
      toothElement.className =
        "tooth bg-gray-200 w-8 h-12 border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex justify-center";

      if (parseInt(toothNumber) <= 16) {
        toothElement.classList.add("rounded-t-full", "items-end");
        toothElement.querySelector("span").className =
          "text-xs font-bold text-gray-700 mb-1";
      } else {
        toothElement.classList.add("rounded-b-full", "items-start");
        toothElement.querySelector("span").className =
          "text-xs font-bold text-gray-700 mt-1";
      }
    }
  });
}

function openToothEditModal(toothNumber) {
  currentEditingTooth = toothNumber;
  const toothData = teethData[toothNumber] || {};
  const toothName = document.querySelector(`[data-tooth="${toothNumber}"]`)
    .dataset.name;

  document.getElementById("edit-tooth-number").textContent = toothNumber;
  document.getElementById("edit-tooth-name").textContent = toothName;
  document.getElementById("edit-tooth-number-input").value = toothNumber;
  document.getElementById("edit-tooth-notes").value = toothData.Notes || "";

  const statusRadios = document.querySelectorAll('input[name="status"]');
  statusRadios.forEach((radio) => {
    radio.checked = radio.value === (toothData.Status || "");
    if (radio.checked) {
      radio.closest("label").classList.add("border-nhd-blue", "bg-blue-50");
    } else {
      radio.closest("label").classList.remove("border-nhd-blue", "bg-blue-50");
    }
  });

  document.getElementById("tooth-edit-modal").classList.remove("hidden");
}

function closeToothEditModal() {
  document.getElementById("tooth-edit-modal").classList.add("hidden");
  currentEditingTooth = null;
}

function updateStatusPreview(status) {
  const statusRadios = document.querySelectorAll('input[name="status"]');
  statusRadios.forEach((radio) => {
    const label = radio.closest("label");
    if (radio.value === status) {
      label.classList.add("border-nhd-blue", "bg-blue-50");
    } else {
      label.classList.remove("border-nhd-blue", "bg-blue-50");
    }
  });
}

function addCondition(condition) {
  const notesTextarea = document.getElementById("edit-tooth-notes");
  const currentNotes = notesTextarea.value.trim();

  if (
    currentNotes &&
    !currentNotes.endsWith(".") &&
    !currentNotes.endsWith(",")
  ) {
    notesTextarea.value = currentNotes + ". " + condition;
  } else if (currentNotes) {
    notesTextarea.value = currentNotes + " " + condition;
  } else {
    notesTextarea.value = condition;
  }

  notesTextarea.focus();
  notesTextarea.setSelectionRange(
    notesTextarea.value.length,
    notesTextarea.value.length,
  );
}

async function saveToothData() {
  if (!currentEditingTooth) return;

  const form = document.getElementById("tooth-edit-form");
  const formData = new FormData(form);
  const status = formData.get("status") || "";
  const notes = formData.get("notes") || "";

  try {
    const response = await fetch(
      `${window.BASE_URL}/doctor/update-dental-chart-item`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          patientId: currentPatientId,
          toothNumber: currentEditingTooth,
          status: status,
          notes: notes,
        }),
      },
    );

    const result = await response.json();

    if (result.success) {
      if (!teethData[currentEditingTooth]) {
        teethData[currentEditingTooth] = {};
      }
      teethData[currentEditingTooth].Status = status;
      teethData[currentEditingTooth].Notes = notes;

      updateTeethDisplay();
      closeToothEditModal();
      if (window.toast) {
        toast.success("Tooth data saved successfully!");
      }
      hasUnsavedChanges = false;
    } else {
      if (window.toast) {
        toast.error(result.message || "Failed to save tooth data");
      }
    }
  } catch (error) {
    console.error("Error saving tooth data:", error);
    if (window.toast) {
      toast.error("Failed to save tooth data");
    }
  }
}

async function markAllTeethAs(status) {
  if (!confirm(`Are you sure you want to mark all teeth as "${status}"?`))
    return;

  try {
    const promises = [];

    for (let i = 1; i <= 32; i++) {
      promises.push(
        fetch(`${window.BASE_URL}/doctor/update-dental-chart-item`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            patientId: currentPatientId,
            toothNumber: i.toString(),
            status: status,
            notes: teethData[i.toString()]?.Notes || "",
          }),
        }).then((response) => response.json()),
      );
    }

    const results = await Promise.all(promises);
    const allSuccessful = results.every((result) => result.success);

    if (allSuccessful) {
      for (let i = 1; i <= 32; i++) {
        if (!teethData[i.toString()]) {
          teethData[i.toString()] = {};
        }
        teethData[i.toString()].Status = status;
      }

      updateTeethDisplay();
      if (window.toast) {
        toast.success(`All teeth marked as "${status}" successfully!`);
      }
      hasUnsavedChanges = false;
    } else {
      if (window.toast) {
        toast.error("Some teeth failed to update");
      }
    }
  } catch (error) {
    console.error("Error updating all teeth:", error);
    if (window.toast) {
      toast.error("Failed to update all teeth");
    }
  }
}

async function clearAllNotes() {
  if (
    !confirm(
      "Are you sure you want to clear all notes? This action cannot be undone.",
    )
  )
    return;

  try {
    const promises = [];

    for (let i = 1; i <= 32; i++) {
      promises.push(
        fetch(`${window.BASE_URL}/doctor/update-dental-chart-item`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            patientId: currentPatientId,
            toothNumber: i.toString(),
            status: teethData[i.toString()]?.Status || "",
            notes: "",
          }),
        }).then((response) => response.json()),
      );
    }

    const results = await Promise.all(promises);
    const allSuccessful = results.every((result) => result.success);

    if (allSuccessful) {
      for (let i = 1; i <= 32; i++) {
        if (teethData[i.toString()]) {
          teethData[i.toString()].Notes = "";
        }
      }

      if (window.toast) {
        toast.success("All notes cleared successfully!");
      }
      hasUnsavedChanges = false;
    } else {
      if (window.toast) {
        toast.error("Some notes failed to clear");
      }
    }
  } catch (error) {
    console.error("Error clearing notes:", error);
    if (window.toast) {
      toast.error("Failed to clear notes");
    }
  }
}

async function saveAllChanges() {
  if (window.toast) {
    toast.info("All changes are automatically saved!");
  }
}

function generateReport() {
  const reportData = {
    patientId: currentPatientId,
    teeth: teethData,
    timestamp: new Date().toISOString(),
  };

  // Create a summary of teeth conditions
  let healthyCount = 0;
  let watchCount = 0;
  let treatmentCount = 0;
  let otherCount = 0;

  for (let i = 1; i <= 32; i++) {
    const tooth = teethData[i.toString()];
    if (tooth && tooth.Status) {
      const status = tooth.Status.toLowerCase();
      if (status.includes("healthy")) healthyCount++;
      else if (status.includes("watch")) watchCount++;
      else if (status.includes("treatment")) treatmentCount++;
      else otherCount++;
    }
  }

  const reportContent = `
Dental Chart Report
Patient ID: ${currentPatientId}
Generated: ${new Date().toLocaleString()}

Summary:
- Healthy teeth: ${healthyCount}
- Teeth requiring monitoring: ${watchCount}
- Teeth needing treatment: ${treatmentCount}
- Other conditions: ${otherCount}

Detailed Notes:
${Object.entries(teethData)
  .filter(([num, data]) => data.Notes)
  .map(
    ([num, data]) =>
      `Tooth #${num}: ${data.Status || "No status"} - ${data.Notes}`,
  )
  .join("\n")}
    `.trim();

  // Create and download the report
  const blob = new Blob([reportContent], { type: "text/plain" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url;
  a.download = `dental-chart-report-patient-${currentPatientId}-${new Date().toISOString().split("T")[0]}.txt`;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);

  if (window.toast) {
    toast.success("Report generated and downloaded!");
  }
}

// Mark changes as unsaved when form inputs change
document.addEventListener("input", function (e) {
  if (e.target.closest("#tooth-edit-form")) {
    hasUnsavedChanges = true;
  }
});
