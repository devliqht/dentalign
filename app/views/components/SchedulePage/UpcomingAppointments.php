<div id="upcoming-section" class="schedule-section hidden">
        <div class="mb-4">
            <h3 class="text-2xl font-semibold text-nhd-brown mb-2">All Upcoming Appointments</h3>
            <p class="text-gray-600 text-sm">Complete schedule overview</p>
        </div>
        
        <?php if (!empty($upcomingAppointments)): ?>
            <div class="space-y-4">
                <?php
                $groupedAppointments = [];
                foreach ($upcomingAppointments as $appointment) {
                    $date = date("Y-m-d", strtotime($appointment["DateTime"]));
                    if (!isset($groupedAppointments[$date])) {
                        $groupedAppointments[$date] = [];
                    }
                    $groupedAppointments[$date][] = $appointment;
                }
                ?>
                
                <?php foreach (
                    $groupedAppointments
                    as $date => $appointments
                ): ?>
                    <div class="glass-card rounded-2xl p-6 border-2 border-gray-200">
                        <h4 class="text-lg font-semibold text-nhd-brown mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo date("l, F j, Y", strtotime($date)); ?>
                            <span class="ml-2 text-sm text-gray-500">(<?php echo count(
                                $appointments
                            ); ?> appointments)</span>
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php foreach ($appointments as $appointment): ?>
                                <div class="glass-card bg-white/40 rounded-2xl p-4 border-gray-200 border-2 hover:bg-white/60 transition-all duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-nhd-blue">
                                            <?php echo date(
                                                "g:i A",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            #<?php echo str_pad(
                                                $appointment["AppointmentID"],
                                                4,
                                                "0",
                                                STR_PAD_LEFT
                                            ); ?>
                                        </span>
                                    </div>
                                    <h5 class="font-semibold text-gray-900 mb-1">
                                        <?php echo htmlspecialchars(
                                            $appointment["PatientFirstName"] .
                                                " " .
                                                $appointment["PatientLastName"]
                                        ); ?>
                                    </h5>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </p>
                                    <p class="text-xs text-gray-500 truncate mb-3">
                                        <?php echo htmlspecialchars(
                                            $appointment["Reason"]
                                        ); ?>
                                    </p>
                                    
                                    <!-- Action buttons -->
                                    <div class="flex space-x-1 mt-2">
                                        <button onclick="openAppointmentDetailsModal(<?php echo $appointment[
                                            "AppointmentID"
                                        ]; ?>)" 
                                                class="glass-card bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                            Details
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No upcoming appointments</h3>
                <p class="text-gray-500">Your schedule is clear for the coming days.</p>
            </div>
        <?php endif; ?>
    </div>