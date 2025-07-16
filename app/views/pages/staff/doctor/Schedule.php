<div class="px-4 pb-8">
    <div class="mb-6">
        <h1 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
            Appointment Schedule
        </h1>
        <p class="text-gray-600 mb-2">
            <?php echo htmlspecialchars($doctor["specialization"]); ?> • 
            <?php echo date("l, F j, Y"); ?>
        </p>
        <div class="flex items-center space-x-4 text-sm text-gray-500">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Today: <?php echo count($todaysAppointments); ?> appointments
            </span>
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Upcoming: <?php echo count(
                    $upcomingAppointments
                ); ?> appointments
            </span>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div class="flex space-x-2">
            <button onclick="showSection('today')" id="today-btn" class="glass-card bg-nhd-blue/80 px-3 py-2 text-sm rounded-2xl text-white font-medium transition-all duration-200">
                Today's Schedule
            </button>
            <button onclick="showSection('calendar')" id="calendar-btn" class="glass-card bg-gray-200/80 px-3 py-2 text-sm rounded-2xl text-gray-700 font-medium transition-all duration-200">
                Calendar View
            </button>
            <button onclick="showSection('upcoming')" id="upcoming-btn" class="glass-card bg-gray-200/80 px-3 py-2 text-sm rounded-2xl text-gray-700 font-medium transition-all duration-200">
                All Upcoming
            </button>
        </div>
        
        <div class="glass-card bg-white/60 border-1 border-gray-200 shadow-md px-4 py-2 rounded-2xl">
            <span class="text-sm font-medium text-gray-700">Selected Date:</span>
            <span id="selected-date-display" class="text-sm font-bold text-nhd-brown ml-2"><?php echo date(
                "l, F j, Y",
                strtotime($selectedDate)
            ); ?></span>
        </div>
    </div>

    <!-- Today's Schedule Section -->
    <?php include "app/views/components/SchedulePage/TodaySchedule.php"; ?>

    <!-- Calendar View Section -->
    <?php include "app/views/components/SchedulePage/CalendarView.php"; ?>

    <!-- Upcoming Appointments Section -->
    <?php include "app/views/components/SchedulePage/UpcomingAppointments.php"; ?>

    <!-- Appointment Details Modal -->
    <?php include "app/views/components/SchedulePage/AppointmentDetailsModal.php"; ?>

    <?php include "app/views/components/SchedulePage/BlockScheduleModal.php"; ?>


</div>

<script>
let currentDate = new Date('<?php echo $selectedDate; ?>');
let selectedDate = new Date('<?php echo $selectedDate; ?>');
let currentWeekStart = new Date('<?php echo $startOfWeek; ?>');
let allAppointments = <?php echo json_encode($upcomingAppointments); ?>;



</script>
<script>
// Add this function to handle timezone-safe date formatting
function getLocalDateString(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// ===== START: NEW CODE FOR BLOCK SCHEDULE FEATURE =====

// A constant array of your clinic's operating hours in a format the database expects.
const clinicHours = [
    "08:00:00", "09:00:00", "10:00:00", "11:00:00", "12:00:00", 
    "13:00:00", "14:00:00", "15:00:00", "16:00:00", "17:00:00"
];

/**
 * Opens the "Block Schedule" modal. It uses the global 'selectedDate'
 * from your calendar logic to know which day to manage.
 */
function openBlockScheduleModal() {
    const modal = document.getElementById('blockScheduleModal');
    const dateDisplay = document.getElementById('block-modal-date-display');
    
    // Use the existing 'selectedDate' global variable
    if (!selectedDate) {
        alert("Please select a date from the calendar first.");
        return;
    }
    
    // Format the date for display in the modal title
    const formattedDate = selectedDate.toLocaleDateString('en-US', { 
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
    });
    dateDisplay.textContent = formattedDate;
    
    modal.classList.remove('hidden');
    
    // Trigger the function to load the slots for the selected day
    loadSlotsForModal(selectedDate);
}

/**
 * Closes the "Block Schedule" modal.
 */
function closeBlockScheduleModal() {
    document.getElementById('blockScheduleModal').classList.add('hidden');
}

/**
 * Handles the submission of the "Block Schedule" form.
 */
async function submitBlockedSlots(event) {
    event.preventDefault();
    
    // FIX: Use getLocalDateString instead of toISOString
    const dateString = getLocalDateString(selectedDate);
    const checkedBoxes = document.querySelectorAll('#blockScheduleForm input[type="checkbox"]:checked');
    
    const timesToBlock = Array.from(checkedBoxes).map(cb => cb.value);

    const apiUrl = `${window.BASE_URL}/doctor/update-blocked-slots`;

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ date: dateString, times: timesToBlock })
        });
        const result = await response.json();

        if (result.success) {
            closeBlockScheduleModal();
            
            // Refresh the view
            loadAppointmentsForDate(selectedDate);
            renderCalendar();
            
            alert('Availability updated successfully!');
        } else {
            alert(result.message || 'Failed to update availability.');
        }
    } catch (error) {
        console.error("Error submitting blocked slots:", error);
        alert('A network error occurred.');
    }
}

/**
 * Fetches availability for the selected date and populates the modal's checkboxes.
 * It will disable slots that are already booked and check slots that are already blocked.
 * @param {Date} date - The date object for which to load slots.
 */
async function loadSlotsForModal(date) {
    const container = document.getElementById('time-slot-checkboxes');
    container.innerHTML = `<div class="text-center col-span-full py-4">Loading slots...</div>`;

    // FIX: Use getLocalDateString instead of toISOString
    const dateString = getLocalDateString(date);
    
    const apiUrl = `${window.BASE_URL}/doctor/get-availability?date=${dateString}`;

    try {
        const response = await fetch(apiUrl);
        const data = await response.json();

        if (data.success) {
            container.innerHTML = ''; // Clear loading message
            
            clinicHours.forEach(time => {
                const isBlocked = data.blocked_times.includes(time);
                const isBooked = data.booked_times.includes(time);
                
                const timeFormatted = new Date(`1970-01-01T${time}`).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

                const slotHtml = `
                    <div class="flex items-center p-2 rounded-lg ${isBooked ? 'bg-gray-200' : 'bg-gray-50'}">
                        <input id="slot-${time}" type="checkbox" value="${time}" name="blocked_slots[]"
                               class="h-4 w-4 text-nhd-blue border-gray-300 rounded focus:ring-nhd-blue"
                               ${isBlocked ? 'checked' : ''}
                               ${isBooked ? 'disabled' : ''}>
                        <label for="slot-${time}" class="ml-3 block text-sm font-medium ${isBooked ? 'text-gray-400 cursor-not-allowed' : 'text-gray-900'}">
                            ${timeFormatted}
                            ${isBooked ? '<span class="text-xs text-red-600 ml-1">(Booked)</span>' : ''}
                        </label>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', slotHtml);
            });
        } else {
            container.innerHTML = `<div class="text-center col-span-full py-4 text-red-500">Failed to load slots.</div>`;
        }
    } catch (error) {
        console.error("Error loading slots:", error);
        container.innerHTML = `<div class="text-center col-span-full py-4 text-red-500">A network error occurred.</div>`;
    }
}
</script>

