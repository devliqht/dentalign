// Global variables to track week navigation
let originalWeekStart; // Store the original week start
let currentWeekOffset = 0; // Track how many weeks we've navigated from original

function showSection(sectionName) {
  document.querySelectorAll(".schedule-section").forEach((section) => {
    section.classList.add("hidden");
  });

  document.querySelectorAll('[id$="-btn"]').forEach((btn) => {
    btn.classList.remove("bg-nhd-blue/80", "text-white");
    btn.classList.add("bg-gray-200/80", "text-gray-700");
  });

  document.getElementById(sectionName + "-section").classList.remove("hidden");

  const activeBtn = document.getElementById(sectionName + "-btn");
  activeBtn.classList.remove("bg-gray-200/80", "text-gray-700");
  activeBtn.classList.add("bg-nhd-blue/80", "text-white");

  if (sectionName === "calendar") {
    initializeCalendar();
  } else if (sectionName === "week") {
    initializeWeekView();
  }
}
// // ===== START: NEW CODE FOR BLOCK SCHEDULE FEATURE =====

// // A constant array of your clinic's operating hours in a format the database expects.
// const clinicHours = [
//     "08:00:00", "09:00:00", "10:00:00", "11:00:00", "12:00:00", 
//     "13:00:00", "14:00:00", "15:00:00", "16:00:00", "17:00:00"
// ];

// /**
//  * Opens the "Block Schedule" modal. It uses the global 'selectedDate'
//  * from your calendar logic to know which day to manage.
//  */
// function openBlockScheduleModal() {
//     const modal = document.getElementById('blockScheduleModal');
//     const dateDisplay = document.getElementById('block-modal-date-display');
    
//     // Use the existing 'selectedDate' global variable
//     if (!selectedDate) {
//         alert("Please select a date from the calendar first.");
//         return;
//     }
    
//     // Format the date for display in the modal title
//     const formattedDate = selectedDate.toLocaleDateString('en-US', { 
//         weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
//     });
//     dateDisplay.textContent = formattedDate;
    
//     modal.classList.remove('hidden');
    
//     // Trigger the function to load the slots for the selected day
//     loadSlotsForModal(selectedDate);
// }

// /**
//  * Closes the "Block Schedule" modal.
//  */
// function closeBlockScheduleModal() {
//     document.getElementById('blockScheduleModal').classList.add('hidden');
// }

// /**
//  * Fetches availability for the selected date and populates the modal's checkboxes.
//  * It will disable slots that are already booked and check slots that are already blocked.
//  * @param {Date} date - The date object for which to load slots.
//  */
// async function loadSlotsForModal(date) {
//     const container = document.getElementById('time-slot-checkboxes');
//     container.innerHTML = `<div class="text-center col-span-full py-4">Loading slots...</div>`;

//     const dateString = date.toISOString().split('T')[0]; // Format as YYYY-MM-DD
    
//     // Assumes BASE_URL is a global variable, which is consistent with your other JS files.
//     const apiUrl = `${window.BASE_URL}/doctor/get-availability?date=${dateString}`;

//     try {
//         const response = await fetch(apiUrl);
//         const data = await response.json();

//         if (data.success) {
//             container.innerHTML = ''; // Clear loading message
            
//             clinicHours.forEach(time => {
//                 const isBlocked = data.blocked_times.includes(time);
//                 const isBooked = data.booked_times.includes(time);
                
//                 // Format the time for user-friendly display (e.g., "8:00 AM")
//                 const timeFormatted = new Date(`1970-01-01T${time}`).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

//                 const slotHtml = `
//                     <div class="flex items-center p-2 rounded-lg ${isBooked ? 'bg-gray-200' : 'bg-gray-50'}">
//                         <input id="slot-${time}" type="checkbox" value="${time}" name="blocked_slots[]"
//                                class="h-4 w-4 text-nhd-blue border-gray-300 rounded focus:ring-nhd-blue"
//                                ${isBlocked ? 'checked' : ''}
//                                ${isBooked ? 'disabled' : ''}>
//                         <label for="slot-${time}" class="ml-3 block text-sm font-medium ${isBooked ? 'text-gray-400 cursor-not-allowed' : 'text-gray-900'}">
//                             ${timeFormatted}
//                             ${isBooked ? '<span class="text-xs text-red-600 ml-1">(Booked)</span>' : ''}
//                         </label>
//                     </div>
//                 `;
//                 container.insertAdjacentHTML('beforeend', slotHtml);
//             });
//         } else {
//             container.innerHTML = `<div class="text-center col-span-full py-4 text-red-500">Failed to load slots.</div>`;
//         }
//     } catch (error) {
//         console.error("Error loading slots:", error);
//         container.innerHTML = `<div class="text-center col-span-full py-4 text-red-500">A network error occurred.</div>`;
//     }
// }

// /**
//  * Handles the submission of the "Block Schedule" form.
//  * It sends the list of checked time slots to the backend.
//  * @param {Event} event - The form submission event.
//  */
// async function submitBlockedSlots(event) {
//     event.preventDefault();
    
//     const dateString = selectedDate.toISOString().split('T')[0];
//     const checkedBoxes = document.querySelectorAll('#blockScheduleForm input[type="checkbox"]:checked');
    
//     const timesToBlock = Array.from(checkedBoxes).map(cb => cb.value);

//     const apiUrl = `${window.BASE_URL}/doctor/update-blocked-slots`;

//     try {
//         const response = await fetch(apiUrl, {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({ date: dateString, times: timesToBlock })
//         });
//         const result = await response.json();

//         if (result.success) {
//             closeBlockScheduleModal();
            
//             // This is the key integration point!
//             // We re-run your existing functions to refresh the view with the new data.
//             loadAppointmentsForDate(selectedDate); // Refreshes the right-side appointment list.
//             renderCalendar(); // Redraws the calendar (useful if you add a "blocked" indicator later).
            
//             alert('Availability updated successfully!');
//         } else {
//             alert(result.message || 'Failed to update availability.');
//         }
//     } catch (error) {
//         console.error("Error submitting blocked slots:", error);
//         alert('A network error occurred.');
//     }
// }
// // ===== END: NEW CODE FOR BLOCK SCHEDULE FEATURE =====

function initializeWeekView() {
  // Store the original week start if not already stored
  if (!originalWeekStart) {
    originalWeekStart = new Date(currentWeekStart);
  }
  // Reset to original week when switching to week view
  currentWeekOffset = 0;
  updateWeekDisplay();
}

function navigateWeek(direction) {
  // Update the offset instead of mutating the date
  currentWeekOffset += direction;
  updateWeekDisplay();
}

function updateWeekDisplay() {
  const currentWeekStartDate = new Date(originalWeekStart);
  currentWeekStartDate.setDate(
    originalWeekStart.getDate() + currentWeekOffset * 7,
  );

  const weekEnd = new Date(currentWeekStartDate);
  weekEnd.setDate(weekEnd.getDate() + 6);

  const weekHeader = document.getElementById("week-header");
  weekHeader.textContent = `Week of ${formatDateRange(currentWeekStartDate, weekEnd)}`;

  updateWeekGrid(currentWeekStartDate);
}

function formatDateRange(startDate, endDate) {
  const startStr = startDate.toLocaleDateString("en-US", {
    month: "short",
    day: "numeric",
  });
  const endStr = endDate.toLocaleDateString("en-US", {
    month: "short",
    day: "numeric",
    year: "numeric",
  });
  return `${startStr} - ${endStr}`;
}

function updateWeekGrid(weekStartDate = null) {
  // Use the provided week start date or calculate from current offset
  const effectiveWeekStart =
    weekStartDate ||
    (() => {
      const calculatedStart = new Date(originalWeekStart || currentWeekStart);
      calculatedStart.setDate(
        (originalWeekStart || currentWeekStart).getDate() +
          currentWeekOffset * 7,
      );
      return calculatedStart;
    })();

  const weekGrid = document.getElementById("week-grid");
  const daysOfWeek = [
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday",
  ];

  weekGrid.innerHTML = "";

  for (let i = 0; i < 7; i++) {
    const dayDate = new Date(effectiveWeekStart);
    dayDate.setDate(dayDate.getDate() + i);

    const isToday = dayDate.toDateString() === new Date().toDateString();
    const dayAppointments = allAppointments.filter((app) => {
      const appDate = new Date(app.DateTime);
      return appDate.toDateString() === dayDate.toDateString();
    });

    const dayCard = document.createElement("div");
    dayCard.className = `glass-card rounded-2xl border-2 shadow-sm p-4 ${isToday ? "bg-nhd-blue/10 border-2 border-nhd-blue/30" : "bg-white/60 border-gray-200"}`;

    dayCard.innerHTML = `
            <div class="text-center mb-3">
                <h4 class="font-semibold text-gray-900 ${isToday ? "text-nhd-blue" : ""}">${daysOfWeek[i]}</h4>
                <p class="text-sm text-gray-600 ${isToday ? "text-nhd-blue/80" : ""}">
                    ${dayDate.toLocaleDateString("en-US", { month: "short", day: "numeric" })}
                    ${isToday ? '<span class="text-xs">(Today)</span>' : ""}
                </p>
            </div>
            ${
              dayAppointments.length > 0
                ? `<div class="space-y-2">
                    ${dayAppointments
                      .map(
                        (app) => `
                        <div class="glass-card bg-white/40 p-3 rounded-xl text-xs">
                            <div class="font-semibold text-nhd-blue">${formatTime(app.DateTime)}</div>
                            <div class="text-gray-900 font-medium">${app.PatientFirstName} ${app.PatientLastName}</div>
                            <div class="text-gray-600 truncate">${app.AppointmentType}</div>
                        </div>
                    `,
                      )
                      .join("")}
                </div>`
                : '<div class="text-center text-gray-400 text-xs py-4">No appointments</div>'
            }
        `;

    weekGrid.appendChild(dayCard);
  }
}

function formatTime(datetime) {
  const date = new Date(datetime);
  return date.toLocaleTimeString("en-US", {
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  });
}

let calendarEventListenersAdded = false;

function initializeCalendar() {
  renderCalendar();
  updateSelectedDateDisplay();
  loadAppointmentsForDate(selectedDate);

  if (!calendarEventListenersAdded) {
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

    calendarEventListenersAdded = true;
  }
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
    const isToday = dayDate.toDateString() === new Date().toDateString();
    const isSelected =
      selectedDate && dayDate.toDateString() === selectedDate.toDateString();

    const hasAppointments = allAppointments.some((app) => {
      const appDate = new Date(app.DateTime);
      return appDate.toDateString() === dayDate.toDateString();
    });

    if (!isCurrentMonth) {
      dayElement.classList.add("other-month");
    } else {
      if (isToday) {
        dayElement.classList.add("today");
      }
      if (isSelected) {
        dayElement.classList.add("selected");
      }
      if (hasAppointments) {
        dayElement.classList.add("has-appointments");
        dayElement.style.position = "relative";
        dayElement.innerHTML = `${dayDate.getDate()}<div style="position: absolute; bottom: 2px; left: 50%; transform: translateX(-50%); width: 4px; height: 4px; background-color: #8b4513; border-radius: 50%;"></div>`;
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
  renderCalendar();
  updateSelectedDateDisplay();
  loadAppointmentsForDate(selectedDate);
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
  }
}

function loadAppointmentsForDate(date) {
  const dateStr = date.toDateString();
  const dayAppointments = allAppointments.filter((app) => {
    const appDate = new Date(app.DateTime);
    return appDate.toDateString() === dateStr;
  });

  const title = document.getElementById("calendar-selected-date-title");
  const container = document.getElementById("calendar-appointments-container");

  title.textContent = date.toLocaleDateString("en-US", {
    weekday: "long",
    month: "long",
    day: "numeric",
  });

  if (dayAppointments.length > 0) {
    container.innerHTML = dayAppointments
      .map(
        (app) => `
            <div class="glass-card rounded-2xl shadow-lg border-2 border-gray-300/70 p-4 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="glass-card bg-nhd-blue/10 text-nhd-blue px-3 py-2 rounded-xl">
                        <div class="text-xs font-medium uppercase tracking-wider">Time</div>
                        <div class="text-lg font-bold">${formatTime(app.DateTime)}</div>
                    </div>
                    <div class="glass-card bg-green-100/60 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                        ID #${String(app.AppointmentID).padStart(4, "0")}
                    </div>
                </div>
                <div class="space-y-2">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">${app.PatientFirstName} ${app.PatientLastName}</h4>
                        <p class="text-sm text-gray-500">${app.PatientEmail}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Type:</span>
                        <p class="text-gray-600">${app.AppointmentType}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Reason:</span>
                        <p class="text-gray-600 text-sm mt-1">${app.Reason}</p>
                    </div>
                    <button onclick="openAppointmentDetailsModal(${app.AppointmentID})"
                            class="glass-card bg-nhd-blue/80 text-white px-3 py-1 rounded-lg text-xs hover:bg-nhd-blue transition-colors">
                            View Details
                      </button>
                </div>
            </div>
        `,
      )
      .join("");
  } else {
    container.innerHTML = `
            <div class="glass-card shadow-sm border-gray-200 bg-gray-50/50 rounded-2xl p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No appointments scheduled</h3>
                <p class="text-gray-500">This day is free in your schedule.</p>
            </div>
        `;
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const hash = window.location.hash.substring(1); // Remove the #
  const validSections = ["today", "calendar", "week", "upcoming"];

  if (hash && validSections.includes(hash)) {
    showSection(hash);
  } else {
    showSection("today");
  }
});
