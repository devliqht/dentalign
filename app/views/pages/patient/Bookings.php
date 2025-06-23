<div class="pb-8">
    <div class="px-4">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">My Bookings <?php echo $user[
            "user_name"
        ]; ?></h2>
        <p class="text-gray-600">View and manage all your dental appointments.</p>
    </div> 
    <div class="flex justify-between items-center mb-4 p-4">
        <div class="flex space-x-2">
            <button onclick="showSection('all')" id="all-btn" class="glass-card bg-nhd-blue/80 px-4 py-2 rounded-2xl text-white">
                All Appointments
            </button>
            <button onclick="showSection('upcoming')" id="upcoming-btn" class="glass-card bg-gray-200/80 px-4 py-2 rounded-2xl text-gray-700">
                Upcoming
            </button>
            <button onclick="showSection('completed')" id="completed-btn" class="glass-card bg-gray-200/80 px-4 py-2 rounded-2xl text-gray-700">
                Completed
            </button>
        </div>
        <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
           class="inline-flex items-center px-4 py-2 glass-card bg-green-600/85 text-white rounded-2xl hover:bg-green-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Book New Appointment
        </a>
    </div>

    <!-- Upcoming Appointments Section -->
    <div id="upcoming-section" class="appointment-section">
        <div class="px-4 mb-3">
            <h3 class="text-2xl font-semibold text-nhd-brown">Upcoming Appointments</h3>
            <p class="text-gray-600 text-sm">Appointments you have scheduled</p>
        </div>
        
        <?php if (!empty($upcomingAppointments)): ?>
            <div class="space-y-4 p-6 glass-card m-4">
                <?php foreach ($upcomingAppointments as $appointment): ?>
                    <div class="glass-card rounded-2xl shadow-md border-l-4 border-l-green-500/70 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 mr-3">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </h3>
                                    <span class="glass-card px-2 py-1 text-xs font-medium rounded-full bg-green-100/40 text-green-800">
                                        Upcoming
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-1">
                                    <strong>Doctor:</strong> Dr. <?php echo htmlspecialchars(
                                        $appointment["DoctorFirstName"] .
                                            " " .
                                            $appointment["DoctorLastName"]
                                    ); ?>
                                    <span class="text-gray-500"> - <?php echo htmlspecialchars(
                                        $appointment["Specialization"]
                                    ); ?></span>
                                </p>
                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>Reason:</strong> <?php echo htmlspecialchars(
                                        $appointment["Reason"]
                                    ); ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Booked on: <?php echo date(
                                        "M j, Y g:i A",
                                        strtotime($appointment["CreatedAt"])
                                    ); ?>
                                </p>
                            </div>
                            <div class="text-right ml-6">
                                <p class="text-lg font-semibold text-green-600">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </p>
                                <p class="text-gray-600">
                                    <?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </p>
                                <div class="mt-2 flex space-x-2">
                                    <button onclick="openRescheduleModal(<?php echo $appointment[
                                        "AppointmentID"
                                    ]; ?>, '<?php echo $appointment[
    "DoctorID"
]; ?>', '<?php echo date(
    "Y-m-d",
    strtotime($appointment["DateTime"])
); ?>', '<?php echo date("H:i", strtotime($appointment["DateTime"])); ?>')" 
                                            class="glass-card text-sm p-2 bg-blue-100/80 text-blue-800 rounded-2xl hover:bg-blue-200">
                                        Reschedule
                                    </button>
                                    <button onclick="confirmCancel(<?php echo $appointment[
                                        "AppointmentID"
                                    ]; ?>)" 
                                            class="glass-card text-sm px-2 py-1 bg-red-100/80 text-red-800 rounded-2xl hover:bg-red-200">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">No upcoming appointments</p>
                <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                   class="inline-flex items-center px-4 py-2 glass-card bg-blue-600/80 text-white rounded-2xl hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Book Your First Appointment
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Completed Appointments Section -->
    <div id="completed-section" class="appointment-section">
        <div class="px-4 mb-3 mt-8">
            <h3 class="text-2xl font-semibold text-nhd-brown">Completed Appointments</h3>
            <p class="text-gray-600 text-sm">Your appointment history</p>
        </div>
        
        <?php if (!empty($completedAppointments)): ?>
            <div class="space-y-4 p-6 glass-card m-4">
                <?php foreach ($completedAppointments as $appointment): ?>
                    <div class="glass-card rounded-2xl shadow-md border-l-4 border-l-gray-400/70 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 mr-3">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </h3>
                                    <span class="glass-card px-2 py-1 text-xs font-medium rounded-full bg-gray-100/40 text-gray-800">
                                        Completed
                                    </span>
                                </div>
                                <p class="text-gray-600 mb-1">
                                    <strong>Doctor:</strong> Dr. <?php echo htmlspecialchars(
                                        $appointment["DoctorFirstName"] .
                                            " " .
                                            $appointment["DoctorLastName"]
                                    ); ?>
                                    <span class="text-gray-500"> - <?php echo htmlspecialchars(
                                        $appointment["Specialization"]
                                    ); ?></span>
                                </p>
                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>Reason:</strong> <?php echo htmlspecialchars(
                                        $appointment["Reason"]
                                    ); ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    Booked on: <?php echo date(
                                        "M j, Y g:i A",
                                        strtotime($appointment["CreatedAt"])
                                    ); ?>
                                </p>
                            </div>
                            <div class="text-right ml-6">
                                <p class="text-lg font-semibold text-gray-600">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </p>
                                <p class="text-gray-600">
                                    <?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">No completed appointments yet</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="fixed inset-0 bg-black/30 backdrop-blur-[1px] hidden items-center justify-center z-50 p-4">
    <div class="glass-card bg-nhd-pale/90 backdrop-blur-sm rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-semibold text-nhd-brown font-family-bodoni">Reschedule Appointment</h3>
            <button type="button" onclick="closeRescheduleModal()" 
                    class="glass-card bg-gray-100/80 hover:bg-gray-200/80 rounded-full p-2 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="rescheduleForm" method="POST" action="<?php echo BASE_URL; ?>/patient/reschedule-appointment">
            <input type="hidden" name="appointment_id" id="reschedule_appointment_id">
            <input type="hidden" name="doctor_id" id="reschedule_doctor_id">
            <input type="hidden" name="new_appointment_date" id="reschedule_new_date">
            <input type="hidden" name="new_appointment_time" id="reschedule_new_time">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[
                "csrf_token"
            ] ?? ""; ?>">
            
            <!-- Current Appointment Info -->
            <div class="glass-card bg-blue-50/50 rounded-2xl p-4 mb-6">
                <h4 class="text-lg font-medium text-nhd-brown mb-2">Current Appointment</h4>
                <div id="current-appointment-info" class="text-sm text-gray-600">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
            
            <!-- Date & Time Selection -->
            <div class="mb-6">
                <label class="block text-xl font-medium tracking-tight font-family-bodoni text-gray-700 mb-4">Select New Date & Time</label>
                
                <div class="flex flex-col lg:flex-row gap-6 w-full">
                    <!-- Calendar Section -->
                    <div class="flex-shrink-0">
                        <h3 class="text-base font-medium text-gray-800 mb-3">Choose Date</h3>
                        <div class="w-[320px] glass-card border border-gray-200 rounded-xl shadow-sm p-4">
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" id="reschedule-prev-month" class="glass-card bg-nhd-blue/80 text-white rounded-lg p-2 hover:bg-nhd-blue transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <h3 id="reschedule-calendar-month-year" class="text-lg font-semibold text-gray-800"></h3>
                                <button type="button" id="reschedule-next-month" class="glass-card bg-nhd-blue/80 text-white rounded-lg p-2 hover:bg-nhd-blue transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Days of Week -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Sun</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Mon</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Tue</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Wed</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Thu</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Fri</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Sat</div>
                            </div>
                            
                            <!-- Calendar Days -->
                            <div id="reschedule-calendar-days" class="grid grid-cols-7 gap-1">
                                <!-- Days will be generated by JavaScript -->
                            </div>
                            
                            <!-- Selected Date Display -->
                            <div class="mt-4 p-3 glass-card bg-gray-50/50 rounded-2xl">
                                <p class="text-sm text-gray-600">Selected Date:</p>
                                <p id="reschedule-selected-date-display" class="text-base font-medium text-nhd-brown">No date selected</p>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots Section -->
                    <div class="flex flex-col flex-1 gap-4">
                        <h3 class="text-base font-medium text-gray-800 mb-3">Choose Time</h3>
                        <div class="glass-card bg-gray-50/30 rounded-2xl p-4">
                            <div id="reschedule-time-slots-container" class="flex flex-wrap gap-3 w-full min-h-[120px]">
                                <div class="w-full text-center py-8">
                                    <p class="text-gray-500 text-sm">Please select a date to view available time slots.</p>
                                </div>
                            </div>
                            
                            <!-- Selected Time Display -->
                            <div class="mt-4 p-3 glass-card bg-white/50 rounded-2xl">
                                <p class="text-sm text-gray-600">Selected Time:</p>
                                <p id="reschedule-selected-time-display" class="text-base font-medium text-nhd-brown">No time selected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="button" onclick="closeRescheduleModal()" 
                        class="flex-1 px-6 py-3 glass-card bg-gray-100/80 text-gray-700 rounded-2xl hover:bg-gray-200/80 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" id="reschedule-submit-btn" disabled
                        class="flex-1 px-6 py-3 glass-card bg-nhd-brown/85 text-white rounded-2xl hover:bg-nhd-brown transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    Reschedule Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold mb-4">Cancel Appointment</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
        
        <form id="cancelForm" method="POST" action="<?php echo BASE_URL; ?>/patient/cancel-appointment">
            <input type="hidden" name="appointment_id" id="cancel_appointment_id">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[
                "csrf_token"
            ] ?? ""; ?>">
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeCancelModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Keep Appointment
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Cancel Appointment
                </button>
            </div>
        </form>
    </div>
</div>



<!-- Server Messages for Toast -->
<script>
window.serverMessages = {
    <?php if (isset($_SESSION["success"])): ?>
        success: <?php echo json_encode($_SESSION["success"]); ?>,
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION["error"])): ?>
        error: <?php echo json_encode($_SESSION["error"]); ?>,
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>
};
</script>

