function filterPatients() {
  const searchTerm = document
    .getElementById("patient-search")
    .value.toLowerCase();
  const recordFilter = document.getElementById("record-filter").value;
  const patientCards = document.querySelectorAll(".patient-card");
  let visibleCount = 0;

  patientCards.forEach((card) => {
    const patientName = card.dataset.patientName;
    const patientEmail = card.dataset.patientEmail;
    const hasRecord = card.dataset.hasRecord;

    const matchesSearch =
      patientName.includes(searchTerm) || patientEmail.includes(searchTerm);

    let matchesFilter = true;
    if (recordFilter === "with-records") {
      matchesFilter = hasRecord === "true";
    } else if (recordFilter === "without-records") {
      matchesFilter = hasRecord === "false";
    }

    if (matchesSearch && matchesFilter) {
      card.style.display = "block";
      visibleCount++;
    } else {
      card.style.display = "none";
    }
  });

  const emptyState = document.getElementById("empty-state");
  const patientsGrid = document.getElementById("patients-grid");

  if (visibleCount === 0) {
    emptyState.classList.remove("hidden");
    patientsGrid.classList.add("hidden");
  } else {
    emptyState.classList.add("hidden");
    patientsGrid.classList.remove("hidden");
  }
}

// Patient detail functions
async function viewPatientDetail(patientId) {
  try {
    document.getElementById("patient-detail-modal").classList.remove("hidden");
    document.getElementById("patient-detail-content").innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-nhd-blue"></div>
            </div>
        `;

    const response = await fetch(
      `${window.BASE_URL}/doctor/get-patient-details?patient_id=${patientId}`,
    );
    const data = await response.json();

    if (data.success) {
      currentPatientData = data;
      renderPatientDetail(data);
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error("Error fetching patient details:", error);
    document.getElementById("patient-detail-content").innerHTML = `
            <div class="text-center py-12">
                <div class="text-red-600 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Error Loading Patient Details</h3>
                <p class="text-gray-500">${error.message}</p>
                <button onclick="viewPatientDetail(${patientId})" class="mt-4 px-4 py-2 bg-nhd-blue text-white rounded-lg hover:bg-nhd-blue/90">
                    Try Again
                </button>
            </div>
        `;
  }
}

function renderPatientDetail(data) {
  const { patient, patientRecord, appointments } = data;

  const content = `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Patient Info -->
            <div class="lg:col-span-1">
                <div class="glass-car rounded-2xl p-6 sticky top-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Patient Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-lg font-semibold text-gray-900">${patient.FirstName} ${patient.LastName}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-700">${patient.Email}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Patient ID</label>
                            <p class="text-gray-700 font-mono">#${String(patient.PatientID).padStart(4, "0")}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Registered</label>
                            <p class="text-gray-700">${new Date(
                              patient.CreatedAt,
                            ).toLocaleDateString("en-US", {
                              year: "numeric",
                              month: "long",
                              day: "numeric",
                            })}</p>
                        </div>
                    </div>

                    ${
                      patientRecord
                        ? `
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-semibold text-gray-900">Medical Record</h4>
                                <button onclick="editPatientRecord(${patientRecord.recordID}, ${patient.PatientID})" 
                                        class="text-sm text-nhd-pale glass-card bg-nhd-blue/80">
                                    Edit
                                </button>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Height:</span>
                                    <span class="font-medium">${patientRecord.height ? patientRecord.height + " cm" : "Not recorded"}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Weight:</span>
                                    <span class="font-medium">${patientRecord.weight ? patientRecord.weight + " kg" : "Not recorded"}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Allergies:</span>
                                    <div class="mt-1">
                                        ${
                                          patientRecord.allergies
                                            ? `<span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs">${patientRecord.allergies}</span>`
                                            : '<span class="text-gray-400 text-xs">None recorded</span>'
                                        }
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Last Visit:</span>
                                    <span class="font-medium">${
                                      patientRecord.lastVisit
                                        ? new Date(
                                            patientRecord.lastVisit,
                                          ).toLocaleDateString()
                                        : "Never"
                                    }</span>
                                </div>
                            </div>
                        </div>
                    `
                        : `
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="text-center">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <p class="text-yellow-800 text-sm mb-3">No medical record found</p>
                                    <button onclick="createPatientRecord(${patient.PatientID})" 
                                            class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                                        Create Record
                                    </button>
                                </div>
                            </div>
                        </div>
                    `
                    }
                </div>
            </div>

            <!-- Appointments and Reports -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Appointment History (${appointments.length})</h3>
                        
                        ${
                          appointments.length > 0
                            ? `
                            <div class="space-y-4">
                                ${appointments
                                  .map(
                                    (appointment) => `
                                    <div class="glass-card shadow-md rounded-xl p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">
                                                    ${appointment.AppointmentType}
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    ${new Date(
                                                      appointment.DateTime,
                                                    ).toLocaleDateString(
                                                      "en-US",
                                                      {
                                                        year: "numeric",
                                                        month: "long",
                                                        day: "numeric",
                                                      },
                                                    )} at ${new Date(
                                                      appointment.DateTime,
                                                    ).toLocaleTimeString(
                                                      "en-US",
                                                      {
                                                        hour: "numeric",
                                                        minute: "2-digit",
                                                        hour12: true,
                                                      },
                                                    )}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-gray-500">Appointment ID</div>
                                                <div class="font-mono text-sm">#${String(appointment.AppointmentID).padStart(4, "0")}</div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="text-sm font-medium text-gray-500">Reason</label>
                                            <p class="text-gray-900">${appointment.Reason}</p>
                                        </div>

                                        <div class="mb-4">
                                            <label class="text-sm font-medium text-gray-500">Doctor</label>
                                            <p class="text-gray-900">Dr. ${appointment.DoctorFirstName} ${appointment.DoctorLastName}</p>
                                            <p class="text-sm text-gray-600">${appointment.Specialization}</p>
                                        </div>

                                        ${
                                          appointment.report
                                            ? `
                                            <div class="bg-nhd-blue/5 rounded-xl p-4 mt-4">
                                                <h5 class="font-medium text-nhd-blue mb-3">Appointment Report</h5>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-nhd-blue">Blood Pressure:</span>
                                                        <p class="font-medium">${appointment.report.BloodPressure || "Not recorded"}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-nhd-blue">Pulse Rate:</span>
                                                        <p class="font-medium">${appointment.report.PulseRate ? appointment.report.PulseRate + " bpm" : "Not recorded"}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-nhd-blue">Temperature:</span>
                                                        <p class="font-medium">${appointment.report.Temperature ? appointment.report.Temperature + "°C" : "Not recorded"}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-nhd-blue">Respiratory:</span>
                                                        <p class="font-medium">${appointment.report.RespiratoryRate ? appointment.report.RespiratoryRate + " /min" : "Not recorded"}</p>
                                                    </div>
                                                </div>
                                                ${
                                                  appointment.report
                                                    .GeneralAppearance
                                                    ? `
                                                    <div class="mt-3">
                                                        <span class="text-blue-700 text-sm">General Appearance:</span>
                                                        <p class="text-blue-900 mt-1">${appointment.report.GeneralAppearance}</p>
                                                    </div>
                                                `
                                                    : ""
                                                }
                                            </div>
                                        `
                                            : `
                                            <div class="bg-gray-50 rounded-xl p-4 mt-4">
                                                <p class="text-gray-600 text-sm">No appointment report available</p>
                                            </div>
                                        `
                                        }
                                    </div>
                                `,
                                  )
                                  .join("")}
                            </div>
                        `
                            : `
                            <div class="text-center py-12 bg-gray-50 rounded-xl">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Appointments</h3>
                                <p class="text-gray-500">This patient has no appointment history.</p>
                            </div>
                        `
                        }
                    </div>
                </div>
            </div>
        </div>
    `;

  document.getElementById("patient-detail-content").innerHTML = content;
}

async function editPatientRecord(recordId, patientId) {
  if (!currentPatientData) {
    alert("Please refresh the patient details first.");
    return;
  }

  const { patient, patientRecord } = currentPatientData;

  document.getElementById("edit-record-id").value = recordId || "";
  document.getElementById("edit-patient-id").value = patientId;
  document.getElementById("edit-height").value = patientRecord?.height || "";
  document.getElementById("edit-weight").value = patientRecord?.weight || "";
  document.getElementById("edit-allergies").value =
    patientRecord?.allergies || "";

  document.getElementById("edit-patient-info").innerHTML = `
        <h4 class="font-semibold text-gray-900 mb-2">${patient.FirstName} ${patient.LastName}</h4>
        <p class="text-sm text-gray-600">${patient.Email}</p>
        <p class="text-xs text-gray-500">Patient ID: #${String(patient.PatientID).padStart(4, "0")}</p>
    `;

  document.getElementById("edit-record-modal").classList.remove("hidden");
}

async function createPatientRecord(patientId) {
  if (!currentPatientData) {
    alert("Please refresh the patient details first.");
    return;
  }

  editPatientRecord(null, patientId);
}

function closePatientDetail() {
  document.getElementById("patient-detail-modal").classList.add("hidden");
  currentPatientData = null;
}

function closeEditRecord() {
  document.getElementById("edit-record-modal").classList.add("hidden");
}

document
  .getElementById("edit-record-form")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
      recordId: formData.get("recordId") || null,
      patientId: formData.get("patientId"),
      height: formData.get("height") || null,
      weight: formData.get("weight") || null,
      allergies: formData.get("allergies") || null,
    };

    try {
      const response = await fetch(
        `${window.BASE_URL}/doctor/update-patient-record-data`,
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
        closeEditRecord();
        await viewPatientDetail(data.patientId);
        location.reload();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error updating patient record:", error);
      alert("Error updating patient record. Please try again.");
    }
  });

document.getElementById("export-btn").addEventListener("click", function () {
  alert("Export functionality will be implemented in the next phase.");
});
