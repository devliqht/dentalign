<div class="px-4 pb-8">
    <div class="mb-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
            Dr. <?php echo htmlspecialchars(
                $doctor["firstName"] . " " . $doctor["lastName"]
            ); ?>'s Schedule
        </h2>
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
            <button onclick="showSection('today')" id="today-btn" class="glass-card bg-nhd-blue/80 px-4 py-2 rounded-2xl text-white font-medium transition-all duration-200">
                Today's Schedule
            </button>
            <button onclick="showSection('calendar')" id="calendar-btn" class="glass-card bg-gray-200/80 px-4 py-2 rounded-2xl text-gray-700 font-medium transition-all duration-200">
                Calendar View
            </button>
            <button onclick="showSection('week')" id="week-btn" class="glass-card bg-gray-200/80 px-4 py-2 rounded-2xl text-gray-700 font-medium transition-all duration-200">
                Week View
            </button>
            <button onclick="showSection('upcoming')" id="upcoming-btn" class="glass-card bg-gray-200/80 px-4 py-2 rounded-2xl text-gray-700 font-medium transition-all duration-200">
                All Upcoming
            </button>
        </div>
        
        <div class="glass-card bg-white/60 px-4 py-2 rounded-2xl">
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

    <!-- Week View Section -->
    <?php include "app/views/components/SchedulePage/WeekView.php"; ?>

    <!-- Upcoming Appointments Section -->
    <?php include "app/views/components/SchedulePage/UpcomingAppointments.php"; ?>

    <!-- Appointment Details Modal -->
    <?php include "app/views/components/SchedulePage/AppointmentDetailsModal.php"; ?>

</div>

<script>
let currentDate = new Date('<?php echo $selectedDate; ?>');
let selectedDate = new Date('<?php echo $selectedDate; ?>');
let currentWeekStart = new Date('<?php echo $startOfWeek; ?>');
let allAppointments = <?php echo json_encode($upcomingAppointments); ?>;
</script>
