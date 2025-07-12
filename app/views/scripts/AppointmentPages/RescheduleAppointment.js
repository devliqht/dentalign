let rescheduleCurrentDate = new Date();
let rescheduleSelectedDate = null;
let rescheduleSelectedTime = null;
const rescheduleToday = new Date();
const rescheduleMinDate = new Date(rescheduleToday);
rescheduleMinDate.setDate(rescheduleToday.getDate() + 1);

function openRescheduleModal(
  appointmentId,
  doctorId,
  currentDate,
  currentTime,
) {
  document.getElementById("reschedule_appointment_id").value = appointmentId;
  document.getElementById("reschedule_doctor_id").value = doctorId;

  rescheduleSelectedDate = null;
  rescheduleSelectedTime = null;
  document.getElementById("reschedule_new_date").value = "";
  document.getElementById("reschedule_new_time").value = "";
  document.getElementById("reschedule-submit-btn").disabled = true;

  initializeRescheduleCalendar();

  document.getElementById("rescheduleModal").classList.remove("hidden");
  document.getElementById("rescheduleModal").classList.add("flex");
}

function closeRescheduleModal() {
  document.getElementById("rescheduleModal").classList.add("hidden");
  document.getElementById("rescheduleModal").classList.remove("flex");
}

function confirmCancel(appointmentId) {
  document.getElementById("cancel_appointment_id").value = appointmentId;
  document.getElementById("cancelModal").classList.remove("hidden");
  document.getElementById("cancelModal").classList.add("flex");
}

function closeCancelModal() {
  document.getElementById("cancelModal").classList.add("hidden");
  document.getElementById("cancelModal").classList.remove("flex");
}

function formatTime(timeSlot) {
  if (!timeSlot) return "";
  const [hours, minutes] = timeSlot.split(":");
  const hour = parseInt(hours);
  const ampm = hour >= 12 ? "PM" : "AM";
  const displayHour = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
  return `${displayHour}:${minutes} ${ampm}`;
}

function initializeRescheduleCalendar() {
  rescheduleCurrentDate = new Date();
  renderRescheduleCalendar();
  updateRescheduleSelectedDateDisplay();

  const prevButton = document.getElementById("reschedule-prev-month");
  const nextButton = document.getElementById("reschedule-next-month");

  if (prevButton) {
    prevButton.replaceWith(prevButton.cloneNode(true));
    document
      .getElementById("reschedule-prev-month")
      .addEventListener("click", function () {
        rescheduleCurrentDate.setMonth(rescheduleCurrentDate.getMonth() - 1);
        renderRescheduleCalendar();
      });
  }

  if (nextButton) {
    nextButton.replaceWith(nextButton.cloneNode(true));
    document
      .getElementById("reschedule-next-month")
      .addEventListener("click", function () {
        rescheduleCurrentDate.setMonth(rescheduleCurrentDate.getMonth() + 1);
        renderRescheduleCalendar();
      });
  }
}

function renderRescheduleCalendar() {
  const monthYear = document.getElementById("reschedule-calendar-month-year");
  const calendarDays = document.getElementById("reschedule-calendar-days");

  if (!monthYear || !calendarDays) {
    console.error("Calendar elements not found:", { monthYear, calendarDays });
    return;
  }

  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];
  monthYear.textContent = `${monthNames[rescheduleCurrentDate.getMonth()]} ${rescheduleCurrentDate.getFullYear()}`;

  calendarDays.innerHTML = "";

  const firstDay = new Date(
    rescheduleCurrentDate.getFullYear(),
    rescheduleCurrentDate.getMonth(),
    1,
  );
  const lastDay = new Date(
    rescheduleCurrentDate.getFullYear(),
    rescheduleCurrentDate.getMonth() + 1,
    0,
  );
  const startDate = new Date(firstDay);
  startDate.setDate(startDate.getDate() - firstDay.getDay());

  for (let i = 0; i < 42; i++) {
    const dayDate = new Date(startDate);
    dayDate.setDate(startDate.getDate() + i);

    const dayElement = document.createElement("div");
    dayElement.className = "calendar-day";
    dayElement.textContent = dayDate.getDate();

    const isCurrentMonth =
      dayDate.getMonth() === rescheduleCurrentDate.getMonth();
    const isToday = dayDate.toDateString() === rescheduleToday.toDateString();
    const isPast = dayDate < rescheduleMinDate;
    const isSelected =
      rescheduleSelectedDate &&
      dayDate.toDateString() === rescheduleSelectedDate.toDateString();

    if (!isCurrentMonth) {
      dayElement.classList.add("other-month");
    } else if (isPast) {
      dayElement.classList.add("disabled");
    } else {
      if (isToday) {
        dayElement.classList.add("today");
      }
      if (isSelected) {
        dayElement.classList.add("selected");
      }

      dayElement.addEventListener("click", function () {
        selectRescheduleDate(dayDate);
      });
    }

    calendarDays.appendChild(dayElement);
  }
}

function selectRescheduleDate(date) {
  rescheduleSelectedDate = new Date(date);
  document.getElementById("reschedule_new_date").value = formatDateForInput(
    rescheduleSelectedDate,
  );
  renderRescheduleCalendar();
  updateRescheduleSelectedDateDisplay();
  updateRescheduleTimeSlots();
}

function formatDateForInput(date) {
  return (
    date.getFullYear() +
    "-" +
    String(date.getMonth() + 1).padStart(2, "0") +
    "-" +
    String(date.getDate()).padStart(2, "0")
  );
}

function updateRescheduleSelectedDateDisplay() {
  const display = document.getElementById("reschedule-selected-date-display");
  if (rescheduleSelectedDate) {
    const options = {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
    };
    display.textContent = rescheduleSelectedDate.toLocaleDateString(
      "en-US",
      options,
    );
  } else {
    display.textContent = "No date selected";
  }
}

function updateRescheduleTimeSlots() {
  const doctorIdElement = document.getElementById("reschedule_doctor_id");
  const dateElement = document.getElementById("reschedule_new_date");
  const timeSlotsContainer = document.getElementById(
    "reschedule-time-slots-container",
  );

  if (!doctorIdElement || !dateElement || !timeSlotsContainer) {
    console.error("Required elements not found:", {
      doctorIdElement,
      dateElement,
      timeSlotsContainer,
    });
    return;
  }

  const doctorId = doctorIdElement.value;
  const date = dateElement.value;

  if (doctorId && date) {
    timeSlotsContainer.innerHTML =
      '<div class="w-full text-center py-8"><p class="text-gray-500 text-sm">Loading available time slots...</p></div>';

    const url = `${window.BASE_URL}/patient/get-timeslots?doctor_id=${doctorId}&date=${date}`;

    fetch(url)
      .then((response) => response.text())
      .then((text) => {
        try {
          const data = JSON.parse(text);

          if (data.success && data.timeSlots && data.timeSlots.length > 0) {
            timeSlotsContainer.innerHTML = "";
            data.timeSlots.forEach((timeSlotData) => {
              const button = document.createElement("button");
              button.type = "button";

              if (timeSlotData.available) {
                button.className =
                  "glass-card px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-nhd-brown focus:ring-offset-2";
                button.addEventListener("click", function () {
                  selectRescheduleTimeSlot(timeSlotData.time, button);
                });
              } else {
                button.className =
                  "glass-card px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-lg transition-all duration-200 disabled";
                button.disabled = true;
              }

              button.setAttribute("data-time", timeSlotData.time);
              button.setAttribute("data-available", timeSlotData.available);
              button.innerHTML =
                formatTime(timeSlotData.time) +
                (timeSlotData.available
                  ? ""
                  : ' <span class="ml-1 text-xs">(Taken)</span>');

              timeSlotsContainer.appendChild(button);
            });
          } else {
            timeSlotsContainer.innerHTML =
              '<div class="w-full text-center py-8"><p class="text-gray-500 text-sm">No available time slots for the selected date.</p></div>';
          }
        } catch (e) {
          console.error("Failed to parse JSON:", e);
          timeSlotsContainer.innerHTML =
            '<div class="w-full text-center py-8"><p class="text-red-500 text-sm">Error loading time slots.</p></div>';
        }
      })
      .catch((error) => {
        console.error("Error fetching time slots:", error);
        timeSlotsContainer.innerHTML =
          '<div class="w-full text-center py-8"><p class="text-red-500 text-sm">Error loading time slots.</p></div>';
      });
  } else {
    timeSlotsContainer.innerHTML =
      '<div class="w-full text-center py-8"><p class="text-gray-500 text-sm">Please select a date to view available time slots.</p></div>';
  }
}

function selectRescheduleTimeSlot(timeSlot, button) {
  if (button.disabled || button.getAttribute("data-available") === "false") {
    return;
  }

  const allTimeButtons = document.querySelectorAll(
    "#reschedule-time-slots-container button",
  );
  allTimeButtons.forEach((btn) => {
    btn.classList.remove("selected");
    btn.classList.remove("bg-nhd-brown/85", "text-white", "border-nhd-brown");
    btn.classList.add("text-gray-700", "border-gray-200");
  });

  button.classList.add("selected");
  button.classList.remove("text-gray-700", "border-gray-200");
  button.classList.add("bg-nhd-brown/85", "text-white", "border-nhd-brown");

  rescheduleSelectedTime = timeSlot;
  document.getElementById("reschedule_new_time").value = timeSlot;
  document.getElementById("reschedule-selected-time-display").textContent =
    formatTime(timeSlot);

  if (rescheduleSelectedDate && rescheduleSelectedTime) {
    document.getElementById("reschedule-submit-btn").disabled = false;
  }
}

// Form validation
document.addEventListener("DOMContentLoaded", function () {
  const rescheduleForm = document.getElementById("rescheduleForm");
  if (rescheduleForm) {
    rescheduleForm.addEventListener("submit", function (e) {
      if (!rescheduleSelectedDate || !rescheduleSelectedTime) {
        e.preventDefault();
        alert(
          "Please select both a date and time for your rescheduled appointment.",
        );
        return false;
      }

      // Confirmation dialog
      if (!confirm("Are you sure you want to reschedule this appointment?")) {
        e.preventDefault();
        return false;
      }
    });
  }
});
