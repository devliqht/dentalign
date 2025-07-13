// Override the fetchAppointmentReport function for dental assistant
async function fetchAppointmentReport(appointmentId) {
  try {
    const response = await fetch(
      `${window.BASE_URL}/dentalassistant/get-appointment-report?appointment_id=${appointmentId}`,
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

// Override the form submission for assistant
document.addEventListener("DOMContentLoaded", function () {
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
        const response = await fetch(`${window.BASE_URL}/dentalassistant/update-appointment-report`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': window.CSRF_TOKEN || ''
          },
          body: JSON.stringify(data)
        });

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