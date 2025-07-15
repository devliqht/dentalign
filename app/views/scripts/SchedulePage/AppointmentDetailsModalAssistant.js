// Override the fetchAppointmentReport function for dental assistant
async function fetchAppointmentReport(appointmentId) {
  try {
    const response = await fetch(
      `${window.BASE_URL}/dentalassistant/get-appointment-report?appointment_id=${appointmentId}`,
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

async function updateAppointmentDoctor(appointmentId, newDoctorId) {
  try {
    const currentDoctorElement = document.getElementById("modalDoctor");
    const currentDoctorName = currentDoctorElement
      ? currentDoctorElement.textContent.trim()
      : "Unknown";

    const doctorSelect = document.getElementById("modalDoctorSelect");
    const newDoctorName = doctorSelect.options[doctorSelect.selectedIndex].text;

    // Prompt for reason
    const reason = prompt("Please provide a reason for changing the doctor:");
    if (!reason || reason.trim() === "") {
      if (window.toast && typeof window.toast.error === "function") {
        window.toast.error("A reason is required to change the doctor");
      }
      return;
    }

    // Get current oral notes content
    const oralNotesTextarea = document.getElementById("oralNotes");
    const currentOralNotes = oralNotesTextarea ? oralNotesTextarea.value : "";

    // Create the doctor change note
    const currentDate = new Date().toLocaleString();
    const doctorChangeNote = `\n\n[${currentDate}] Doctor changed from ${currentDoctorName} to ${newDoctorName} because of: ${reason.trim()}`;

    // Append the note to current oral notes
    const updatedOralNotes = currentOralNotes + doctorChangeNote;

    const response = await fetch(
      `${window.BASE_URL}/dentalassistant/update-appointment-doctor`,
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          appointmentId: appointmentId,
          doctorId: newDoctorId,
          oralNotes: updatedOralNotes,
          changeReason: reason.trim(),
        }),
      },
    );

    const result = await response.json();

    if (result.success) {
      if (window.toast && typeof window.toast.success === "function") {
        window.toast.success(result.message || "Doctor updated successfully");
      }

      // Update the oral notes textarea immediately with the new content
      if (oralNotesTextarea) {
        oralNotesTextarea.value = updatedOralNotes;
      }

      // Refresh the appointment data
      await fetchAppointmentReport(appointmentId);

      // Reset the dropdown
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

// Override the form submission for assistant
document.addEventListener("DOMContentLoaded", function () {
  // Add event listener for the update doctor button (dental assistant specific)
  const updateDoctorBtn = document.getElementById("updateDoctorBtn");
  if (updateDoctorBtn) {
    // Remove existing event listeners by cloning the button
    const newUpdateBtn = updateDoctorBtn.cloneNode(true);
    updateDoctorBtn.parentNode.replaceChild(newUpdateBtn, updateDoctorBtn);

    newUpdateBtn.addEventListener("click", function () {
      const appointmentId = currentAppointmentId;
      const newDoctorId = document.getElementById("modalDoctorSelect").value;

      if (!newDoctorId) {
        if (window.toast && typeof window.toast.error === "function") {
          window.toast.error("Please select a doctor");
        }
        return;
      }

      if (
        !confirm(
          "Are you sure you want to change the doctor for this appointment? You will be asked to provide a reason.",
        )
      ) {
        return;
      }

      updateAppointmentDoctor(appointmentId, newDoctorId);
    });
  }
  const form = document.getElementById("appointmentReportForm");
  if (form) {
    // Remove existing event listeners by cloning the form
    const newForm = form.cloneNode(true);
    form.parentNode.replaceChild(newForm, form);

    newForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      if (!confirm("Are you sure you want to save this appointment report?")) {
        return;
      }

      const formData = new FormData(newForm);
      const data = {
        appointmentId: formData.get("appointmentId"),
        oralNotes: formData.get("oralNotes"),
        diagnosis: formData.get("diagnosis"),
        xrayImages: formData.get("xrayImages"),
        status: formData.get("modalStatus"),
      };

      try {
        const response = await fetch(
          `${window.BASE_URL}/dentalassistant/update-appointment-report`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-Token": window.CSRF_TOKEN || "",
            },
            body: JSON.stringify(data),
          },
        );

        const result = await response.json();

        if (result.success) {
          if (window.toast) {
            toast.success("Appointment report saved successfully!");
          } else {
            alert("Appointment report saved successfully!");
          }
          closeAppointmentDetailsModal();

          // Reload the page to show updated data
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          if (window.toast) {
            toast.error(result.message || "Failed to save appointment report");
          } else {
            alert(result.message || "Failed to save appointment report");
          }
        }
      } catch (error) {
        console.error("Error saving appointment report:", error);
        if (window.toast) {
          toast.error("Network error occurred while saving");
        } else {
          alert("Network error occurred while saving");
        }
      }
    });
  }
});
