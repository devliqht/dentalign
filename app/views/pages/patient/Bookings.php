<div class="max-w-4xl px-4 py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">My Bookings</h2>
        <p class="text-gray-600">View and manage all your dental appointments.</p>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-2">
            <button onclick="filterAppointments('all')" class="filter-btn active px-4 py-2 rounded-md bg-blue-600 text-white" data-filter="all">
                All Appointments
            </button>
            <button onclick="filterAppointments('upcoming')" class="filter-btn px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="upcoming">
                Upcoming
            </button>
            <button onclick="filterAppointments('past')" class="filter-btn px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300" data-filter="past">
                Past
            </button>
        </div>
        <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Book New Appointment
        </a>
    </div>

    <?php if (!empty($appointments)): ?>
        <div class="space-y-4" id="appointments-container">
            <?php foreach ($appointments as $appointment): 
                $isUpcoming = strtotime($appointment['DateTime']) > time();
                $statusClass = $isUpcoming ? 'border-l-green-500 bg-green-50' : 'border-l-gray-400 bg-gray-50';
                $dateClass = $isUpcoming ? 'text-green-600' : 'text-gray-600';
            ?>
                <div class="appointment-card bg-white rounded-lg shadow-md border-l-4 <?php echo $statusClass; ?> p-6" 
                     data-status="<?php echo $isUpcoming ? 'upcoming' : 'past'; ?>">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 mr-3">
                                    <?php echo htmlspecialchars($appointment['AppointmentType']); ?>
                                </h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $isUpcoming ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $isUpcoming ? 'Upcoming' : 'Completed'; ?>
                                </span>
                            </div>
                            <p class="text-gray-600 mb-1">
                                <strong>Doctor:</strong> Dr. <?php echo htmlspecialchars($appointment['DoctorFirstName'] . ' ' . $appointment['DoctorLastName']); ?>
                                <span class="text-gray-500"> - <?php echo htmlspecialchars($appointment['Specialization']); ?></span>
                            </p>
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Reason:</strong> <?php echo htmlspecialchars($appointment['Reason']); ?>
                            </p>
                            <p class="text-xs text-gray-500">
                                Booked on: <?php echo date('M j, Y g:i A', strtotime($appointment['CreatedAt'])); ?>
                            </p>
                        </div>
                        <div class="text-right ml-6">
                            <p class="text-lg font-semibold <?php echo $dateClass; ?>">
                                <?php echo date('M j, Y', strtotime($appointment['DateTime'])); ?>
                            </p>
                            <p class="text-gray-600">
                                <?php echo date('g:i A', strtotime($appointment['DateTime'])); ?>
                            </p>
                            <?php if ($isUpcoming): ?>
                                <div class="mt-2 flex space-x-2">
                                    <button class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded hover:bg-blue-200">
                                        Reschedule
                                    </button>
                                    <button class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded hover:bg-red-200">
                                        Cancel
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500 mb-4">No appointments found</p>
            <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Book Your First Appointment
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function filterAppointments(filter) {
    const appointments = document.querySelectorAll('.appointment-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    buttons.forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    const activeButton = document.querySelector(`[data-filter="${filter}"]`);
    activeButton.classList.remove('bg-gray-200', 'text-gray-700');
    activeButton.classList.add('bg-blue-600', 'text-white');
    
    appointments.forEach(appointment => {
        if (filter === 'all') {
            appointment.style.display = 'block';
        } else {
            const status = appointment.getAttribute('data-status');
            appointment.style.display = status === filter ? 'block' : 'none';
        }
    });
}
</script>
