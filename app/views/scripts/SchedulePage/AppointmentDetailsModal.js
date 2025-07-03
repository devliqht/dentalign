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
