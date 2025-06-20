<div class="max-w-4xl px-4 py-8">
    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4"><?php echo $_SESSION['user_type'] . ' Dashboard'; ?></h2>
        <p class="text-gray-600 mb-4">You have successfully logged into your account. This is your dashboard where you can manage your dental care activities.</p>
    </div>
    
    <div class="flex flex-row gap-4 p-6">
        <div class="flex flex-col w-full">
            <h1 class="text-2xl font-bold font-family-sans text-nhd-blue mb-6">Upcoming Appointments</h1>
            
            <?php if (!empty($upcomingAppointments)): ?>
                <div class="space-y-4">
                    <?php foreach ($upcomingAppointments as $appointment): ?>
                        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <?php echo htmlspecialchars($appointment['AppointmentType']); ?>
                                    </h3>
                                    <p class="text-gray-600 mt-1">
                                        with Dr. <?php echo htmlspecialchars($appointment['DoctorFirstName'] . ' ' . $appointment['DoctorLastName']); ?>
                                        - <?php echo htmlspecialchars($appointment['Specialization']); ?>
                                    </p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        Reason: <?php echo htmlspecialchars($appointment['Reason']); ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-blue-600">
                                        <?php echo date('M j, Y', strtotime($appointment['DateTime'])); ?>
                                    </p>
                                    <p class="text-gray-600">
                                        <?php echo date('g:i A', strtotime($appointment['DateTime'])); ?>
                                    </p>
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
                    <p class="text-gray-500 mb-4">No upcoming appointments scheduled</p>
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
    </div>
</div> 