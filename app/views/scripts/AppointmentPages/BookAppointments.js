document.addEventListener("DOMContentLoaded", function () {
  const doctorIdInput = document.getElementById("doctor_id");
  const dateInput = document.getElementById("appointment_date");
  const timeInput = document.getElementById("appointment_time");
  const appointmentTypeInput = document.getElementById("appointment_type");
  const timeSlotsContainer = document.getElementById("time-slots-container");
  const selectedTimeDisplay = document.getElementById("selected-time-display");
  const selectedTypeDisplay = document.getElementById("selected-type-display");
  const doctorCards = document.querySelectorAll(".doctor-card");
  const appointmentTypeCards = document.querySelectorAll(
    ".appointment-type-card",
  );

  let currentDate = new Date();
  let selectedDate = null;
  const today = new Date();
  const minDate = new Date(today);
  minDate.setDate(today.getDate() + 1);

  initializeCalendar();

  doctorCards.forEach((card) => {
    card.addEventListener("click", function () {
      const doctorId = this.getAttribute("data-doctor-id");

      doctorCards.forEach((c) => {
        c.classList.remove("selected", "border-nhd-brown", "shadow-lg");
        c.classList.add("border-gray-200");

        const indicator = c.querySelector(".absolute.top-3.right-3");
        indicator.classList.remove("bg-nhd-brown", "border-nhd-brown");
        indicator.classList.add("border-gray-300");
        const innerCircle = indicator.querySelector("div");
        innerCircle.classList.remove("scale-75");
        innerCircle.classList.add("scale-50");
      });

      this.classList.add("selected", "border-nhd-brown", "shadow-lg");
      this.classList.remove("border-gray-200");

      const indicator = this.querySelector(".absolute.top-3.right-3");
      indicator.classList.add("bg-nhd-brown", "border-nhd-brown");
      indicator.classList.remove("border-gray-300");
      const innerCircle = indicator.querySelector("div");
      innerCircle.classList.add("scale-75");
      innerCircle.classList.remove("scale-50");

      doctorIdInput.value = doctorId;

      console.log("Doctor selected, calling updateTimeSlots()");
      updateTimeSlots();
    });
  });

  const appointmentForm = document.getElementById("appointment-form");
  if (appointmentForm) {
    appointmentForm.addEventListener("submit", function (e) {
      const doctorId = doctorIdInput.value;
      const date = dateInput.value;
      const time = timeInput.value;
      const appointmentType = appointmentTypeInput.value;
      const reason = document.getElementById("reason").value;

      if (!doctorId) {
        e.preventDefault();
        if (window.toast) {
          toast.error("Please select a doctor");
        } else {
          alert("Please select a doctor");
        }
        return false;
      }

      if (!date) {
        e.preventDefault();
        if (window.toast) {
          toast.error("Please select an appointment date");
        } else {
          alert("Please select an appointment date");
        }
        return false;
      }

      if (!time) {
        e.preventDefault();
        if (window.toast) {
          toast.error("Please select an appointment time");
        } else {
          alert("Please select an appointment time");
        }
        return false;
      }

      if (!appointmentType) {
        e.preventDefault();
        if (window.toast) {
          toast.error("Please select an appointment type");
        } else {
          alert("Please select an appointment type");
        }
        return false;
      }

      if (!reason || reason.length < 10) {
        e.preventDefault();
        if (window.toast) {
          toast.error(
            "Please provide a reason for your visit (at least 10 characters)",
          );
        } else {
          alert(
            "Please provide a reason for your visit (at least 10 characters)",
          );
        }
        return false;
      }

      console.log("Form validation passed, submitting with data:", {
        doctorId,
        date,
        time,
        appointmentType,
        reason: reason.substring(0, 50) + "...",
      });

      if (window.toast) {
        toast.info("Submitting appointment booking...", 2000);
      }

      return true;
    });
  }

  appointmentTypeCards.forEach((card) => {
    card.addEventListener("click", function () {
      const appointmentType = this.getAttribute("data-type");

      appointmentTypeCards.forEach((c) => {
        c.classList.remove("selected");
        c.classList.remove("bg-nhd-brown/85", "text-white", "border-nhd-brown");
        c.classList.add("text-gray-700", "border-gray-200");
      });

      this.classList.add("selected");
      this.classList.remove("text-gray-700", "border-gray-200");
      this.classList.add("bg-nhd-brown/85", "text-white", "border-nhd-brown");

      appointmentTypeInput.value = appointmentType;
      selectedTypeDisplay.textContent = appointmentType;
    });
  });

  function updateTimeSlots() {
    const doctorId = doctorIdInput.value;
    const date = dateInput.value;

    console.log("updateTimeSlots called with:", { doctorId, date });

    if (doctorId && date) {
      timeSlotsContainer.innerHTML =
        '<div class="w-full text-center py-8"><p class="text-gray-500 text-sm">Loading available time slots...</p></div>';

      const url = `${window.BASE_URL}/patient/get-timeslots?doctor_id=${doctorId}&date=${date}`;
      console.log("Making AJAX request to:", url);

      fetch(url)
        .then((response) => {
          console.log("Response status:", response.status);
          console.log("Response headers:", response.headers);
          return response.text();
        })
        .then((text) => {
          console.log("Raw response:", text);
          try {
            const data = JSON.parse(text);
            console.log("Parsed data:", data);

            if (data.success && data.timeSlots && data.timeSlots.length > 0) {
              timeSlotsContainer.innerHTML = "";
              data.timeSlots.forEach((timeSlotData) => {
                const button = document.createElement("button");
                button.type = "button";

                if (timeSlotData.available) {
                  button.className =
                    "glass-card px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-lg hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-nhd-brown focus:ring-offset-2";
                  button.addEventListener("click", function () {
                    selectTimeSlot(timeSlotData.time, button);
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
            console.error("Raw text was:", text);
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
      console.log("Either doctor or date is missing - not fetching timeslots");
      timeSlotsContainer.innerHTML =
        '<div class="w-full text-center py-8"><p class="text-gray-500 text-sm">Please select a doctor and date to view available time slots.</p></div>';
    }
  }

  function selectTimeSlot(timeSlot, button) {
    if (button.disabled || button.getAttribute("data-available") === "false") {
      return;
    }

    const allTimeButtons = document.querySelectorAll(
      "#time-slots-container button",
    );
    allTimeButtons.forEach((btn) => {
      btn.classList.remove("selected");
      btn.classList.remove("bg-nhd-brown", "text-white", "border-nhd-brown");
      btn.classList.add("text-gray-700", "border-gray-200");
    });

    button.classList.add("selected");
    button.classList.remove("text-gray-700", "border-gray-200");
    button.classList.add("bg-nhd-brown", "text-white", "border-nhd-brown");

    timeInput.value = timeSlot;
    selectedTimeDisplay.textContent = formatTime(timeSlot);
  }

  function formatTime(timeSlot) {
    const [hours, minutes] = timeSlot.split(":");
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? "PM" : "AM";
    const displayHour = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
    return `${displayHour}:${minutes} ${ampm}`;
  }

  function initializeCalendar() {
    // set initial date if there's a selected date from PHP
    const initialDate = dateInput.value;
    if (initialDate) {
      selectedDate = new Date(initialDate);
      currentDate = new Date(selectedDate);
    }

    renderCalendar();
    updateSelectedDateDisplay();

    document
      .getElementById("prev-month")
      .addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
      });

    document
      .getElementById("next-month")
      .addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
      });
  }

  function renderCalendar() {
    const monthYear = document.getElementById("calendar-month-year");
    const calendarDays = document.getElementById("calendar-days");

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
    monthYear.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;

    calendarDays.innerHTML = "";

    const firstDay = new Date(
      currentDate.getFullYear(),
      currentDate.getMonth(),
      1,
    );
    const lastDay = new Date(
      currentDate.getFullYear(),
      currentDate.getMonth() + 1,
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

      const isCurrentMonth = dayDate.getMonth() === currentDate.getMonth();
      const isToday = dayDate.toDateString() === today.toDateString();
      const isPast = dayDate < minDate;
      const isSelected =
        selectedDate && dayDate.toDateString() === selectedDate.toDateString();

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
          selectDate(dayDate);
        });
      }

      calendarDays.appendChild(dayElement);
    }
  }

  function selectDate(date) {
    selectedDate = new Date(date);
    dateInput.value = formatDateForInput(selectedDate);
    renderCalendar();
    updateSelectedDateDisplay();
    updateTimeSlots();
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

  function updateSelectedDateDisplay() {
    const display = document.getElementById("selected-date-display");
    if (selectedDate) {
      const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      };
      display.textContent = selectedDate.toLocaleDateString("en-US", options);
    } else {
      display.textContent = "No date selected";
    }
  }

  document
    .getElementById("appointment-form")
    .addEventListener("submit", function (e) {
      const reason = document.getElementById("reason").value;
      const doctorId = doctorIdInput.value;
      const appointmentDate = dateInput.value;
      const appointmentTime = timeInput.value;
      const appointmentType = appointmentTypeInput.value;

      if (!doctorId) {
        e.preventDefault();
        if (window.toast) {
          toast.error("Please select a doctor.");
        } else {
          alert("Please select a doctor.");
        }
        return false;
      }

      if (!appointmentDate) {
        e.preventDefault();
        if (window.toast) {
          toast.error("Please select an appointment date.");
        } else {
          alert("Please select an appointment date.");
        }
        return false;
      }

      if (!appointmentTime) {
        e.preventDefault();
        if (window.toast) {
          toast.error("Please select an appointment time.");
        } else {
          alert("Please select an appointment time.");
        }
        return false;
      }

      if (!appointmentType) {
        e.preventDefault();
        if (window.toast) {
          toast.error("Please select an appointment type.");
        } else {
          alert("Please select an appointment type.");
        }
        return false;
      }

      if (reason.length < 10) {
        e.preventDefault();
        if (window.toast) {
          toast.error(
            "Please provide a reason for your visit (at least 10 characters).",
          );
        } else {
          alert(
            "Please provide a reason for your visit (at least 10 characters).",
          );
        }
        return false;
      }
    });
});
