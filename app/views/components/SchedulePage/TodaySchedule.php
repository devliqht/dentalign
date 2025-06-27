<div id="today-section" class="schedule-section">
        <div class="mb-4">
            <h3 class="text-2xl font-semibold text-nhd-brown mb-2">Today's Appointments</h3>
            <p class="text-gray-600 text-sm"><?php echo date(
                "l, F j, Y"
            ); ?></p>
        </div>
        
        <?php if (!empty($todaysAppointments)): ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <?php foreach ($todaysAppointments as $appointment): ?>
                    <div class="glass-card rounded-2xl shadow-lg border-2 border-gray-300/70 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group">
                        <!-- Time Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="glass-card bg-nhd-blue/10 text-nhd-blue px-3 py-2 rounded-xl">
                                <div class="text-xs font-medium uppercase tracking-wider">Appointment Time</div>
                                <div class="text-lg font-bold">
                                    <?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </div>
                            </div>
                            <div class="glass-card bg-green-100/60 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                ID #<?php echo str_pad(
                                    $appointment["AppointmentID"],
                                    4,
                                    "0",
                                    STR_PAD_LEFT
                                ); ?>
                            </div>
                        </div>

                        <!-- Patient Information -->
                        <div class="space-y-3">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-nhd-blue transition-colors">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientFirstName"] .
                                            " " .
                                            $appointment["PatientLastName"]
                                    ); ?>
                                </h4>
                                <p class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientEmail"]
                                    ); ?>
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Type:</span>
                                    <p class="text-gray-600"><?php echo htmlspecialchars(
                                        $appointment["AppointmentType"]
                                    ); ?></p>
                                </div>
                            </div>

                            <div>
                                <span class="font-medium text-gray-700">Reason:</span>
                                <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars(
                                    $appointment["Reason"]
                                ); ?></p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
                            <button onclick="openAppointmentDetailsModal(<?php echo $appointment[
                                "AppointmentID"
                            ]; ?>)" 
                                    class="glass-card bg-nhd-blue/80 text-white px-3 py-1 rounded-lg text-xs hover:bg-nhd-blue transition-colors">
                                View Details
                            </button>
                            <button class="glass-card bg-gray-200/80 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-300/80 transition-colors">
                                Add Notes
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center border-2 border-gray-200">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No appointments today</h3>
                <p class="text-gray-500">You have a free day! Enjoy your time off.</p>
            </div>
        <?php endif; ?>
    </div>