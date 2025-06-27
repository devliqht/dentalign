<div class="px-4 pb-8">
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
                                Appointment History
                            </h1>
                            <p class="text-gray-600 mb-2">
                                Dr. <?php echo htmlspecialchars(
                                    $doctor["firstName"] .
                                        " " .
                                        $doctor["lastName"]
                                ); ?>
                                • <?php echo htmlspecialchars(
                                    $doctor["specialization"]
                                ); ?>
                            </p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Total completed: <?php echo count(
                                        $appointmentHistory
                                    ); ?> appointments
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="<?php echo BASE_URL; ?>/doctor/schedule" class="glass-card bg-nhd-blue/80 text-white px-6 py-3 rounded-2xl font-medium hover:bg-nhd-blue transition-all duration-200">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Current Schedule
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <?php
                    $totalAppointments = count($appointmentHistory);
                    $thisMonth = 0;
                    $thisWeek = 0;
                    $currentMonth = date("Y-m");
                    $currentWeekStart = date(
                        "Y-m-d",
                        strtotime("monday this week")
                    );
                    $currentWeekEnd = date(
                        "Y-m-d",
                        strtotime("sunday this week")
                    );

                    foreach ($appointmentHistory as $appointment) {
                        $appointmentDate = date(
                            "Y-m-d",
                            strtotime($appointment["DateTime"])
                        );
                        $appointmentMonth = date(
                            "Y-m",
                            strtotime($appointment["DateTime"])
                        );

                        if ($appointmentMonth === $currentMonth) {
                            $thisMonth++;
                        }

                        if (
                            $appointmentDate >= $currentWeekStart &&
                            $appointmentDate <= $currentWeekEnd
                        ) {
                            $thisWeek++;
                        }
                    }
                    ?>
                    
                    <div class="glass-card bg-white/60 rounded-2xl p-6 shadow-lg border-2 border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900"><?php echo $totalAppointments; ?></p>
                                <p class="text-gray-600">Total Completed</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card bg-white/60 rounded-2xl p-6 shadow-lg border-2 border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900"><?php echo $thisMonth; ?></p>
                                <p class="text-gray-600">This Month</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card bg-white/60 rounded-2xl p-6 shadow-lg border-2 border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900"><?php echo $thisWeek; ?></p>
                                <p class="text-gray-600">This Week</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment History List -->
                <div class="glass-card bg-white/60 rounded-2xl shadow-lg border border-gray-200/50 p-6">
                    <h3 class="text-2xl font-semibold text-nhd-brown mb-6">Completed Appointments</h3>
                    
                    <?php if (!empty($appointmentHistory)): ?>
                        <div class="space-y-4">
                            <?php foreach (
                                $appointmentHistory
                                as $appointment
                            ): ?>
                                <div class="glass-card bg-white/80 rounded-2xl border-2 border-gray-200 p-6 hover:shadow-lg transition-all duration-300">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                        <!-- Left side - Appointment details -->
                                        <div class="flex-1">
                                            <div class="flex items-start space-x-4">
                                                <!-- Date & Time -->
                                                <div class="glass-card bg-nhd-blue/10 text-nhd-blue px-4 py-2 rounded-lg min-w-[120px]">
                                                    <div class="text-xs font-medium uppercase tracking-wider">
                                                        <?php echo date(
                                                            "M j",
                                                            strtotime(
                                                                $appointment[
                                                                    "DateTime"
                                                                ]
                                                            )
                                                        ); ?>
                                                    </div>
                                                    <div class="text-sm font-bold">
                                                        <?php echo date(
                                                            "g:i A",
                                                            strtotime(
                                                                $appointment[
                                                                    "DateTime"
                                                                ]
                                                            )
                                                        ); ?>
                                                    </div>
                                                </div>
                                                
                                                <!-- Patient Information -->
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                                        <?php echo htmlspecialchars(
                                                            $appointment[
                                                                "PatientFirstName"
                                                            ] .
                                                                " " .
                                                                $appointment[
                                                                    "PatientLastName"
                                                                ]
                                                        ); ?>
                                                    </h4>
                                                    <p class="text-sm text-gray-500 mb-2">
                                                        <?php echo htmlspecialchars(
                                                            $appointment[
                                                                "PatientEmail"
                                                            ]
                                                        ); ?>
                                                    </p>
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                        <div>
                                                            <span class="font-medium text-gray-700">Type:</span>
                                                            <span class="text-gray-600 ml-1"><?php echo htmlspecialchars(
                                                                $appointment[
                                                                    "AppointmentType"
                                                                ]
                                                            ); ?></span>
                                                        </div>
                                                        <div>
                                                            <span class="font-medium text-gray-700">Date:</span>
                                                            <span class="text-gray-600 ml-1"><?php echo date(
                                                                "F j, Y",
                                                                strtotime(
                                                                    $appointment[
                                                                        "DateTime"
                                                                    ]
                                                                )
                                                            ); ?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if (
                                                        !empty(
                                                            $appointment[
                                                                "Reason"
                                                            ]
                                                        )
                                                    ): ?>
                                                        <div class="mt-2">
                                                            <span class="font-medium text-gray-700">Reason:</span>
                                                            <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars(
                                                                $appointment[
                                                                    "Reason"
                                                                ]
                                                            ); ?></p>
                                                        </div>
                                                                        <?php endif; ?>
                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Right side - Status and actions -->
                                        <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col items-end space-y-2">
                                            <div class="glass-card bg-green-100/60 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                                Completed
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ID #<?php echo str_pad(
                                                    $appointment[
                                                        "AppointmentID"
                                                    ],
                                                    4,
                                                    "0",
                                                    STR_PAD_LEFT
                                                ); ?>
                                            </div>
                                            
                                            <!-- Action buttons -->
                                            <div class="flex space-x-2 mt-2">
                                                <button onclick="openAppointmentDetailsModal(<?php echo $appointment[
                                                    "AppointmentID"
                                                ]; ?>)" 
                                                        class="glass-card bg-nhd-blue/80 text-white px-3 py-1 rounded-lg text-xs hover:bg-nhd-blue transition-colors">
                                                    View Details
                                                </button>
                                                <button class="glass-card bg-gray-200/80 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-300/80 transition-colors">
                                                    Notes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination placeholder -->
                        <?php if (count($appointmentHistory) > 10): ?>
                            <div class="mt-8 flex justify-center">
                                <div class="glass-card bg-white/60 rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Showing first <?php echo min(
                                        50,
                                        count($appointmentHistory)
                                    ); ?> appointments</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <!-- Empty state -->
                        <div class="text-center py-12">
                            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No appointment history yet</h3>
                            <p class="text-gray-500 mb-4">Your completed appointments will appear here once you start seeing patients.</p>
                            <a href="<?php echo BASE_URL; ?>/doctor/schedule" class="glass-card bg-nhd-blue/80 text-white px-6 py-2 rounded-lg font-medium hover:bg-nhd-blue transition-colors">
                                View Current Schedule
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
</div>

<!-- Appointment Details Modal -->
<?php include "app/views/components/SchedulePage/AppointmentDetailsModal.php"; ?>
