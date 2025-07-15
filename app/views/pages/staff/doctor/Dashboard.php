<div class="mx-auto px-4 pb-4">
    <div class="mb-8">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
            Doctor Dashboard
        </h2>
        <p class="text-gray-600 mb-4">
            Welcome back, Dr. <?php echo htmlspecialchars(
                $user["name"]
            ); ?>! Here's your comprehensive practice overview.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Appointments -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-nhd-blue/80">Total Appointments</p>
                    <p class="text-3xl font-bold text-nhd-blue"><?php echo $appointmentStats[
                        "total"
                    ]; ?></p>
                </div>
                <div class="p-3 bg-nhd-blue/20 rounded-full">
                    <svg class="w-6 h-6 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-700">Today's Schedule</p>
                    <p class="text-3xl font-bold text-purple-800"><?php echo $appointmentStats[
                        "today"
                    ]; ?></p>
                </div>
                <div class="p-3 bg-purple-200/50 rounded-full">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <a href="<?php echo BASE_URL; ?>/doctor/appointment-history"  class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-700">Upcoming</p>
                    <p class="text-3xl font-bold text-yellow-800"><?php echo $appointmentStats[
                        "upcoming"
                    ]; ?></p>
                </div>
                <div class="p-3 bg-nhd-pale/50 rounded-full">
                    <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Completed Appointments -->
        <a href="<?php echo BASE_URL; ?>/doctor/appointment-history?filter=completed" class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-700">Completed</p>
                    <p class="text-3xl font-bold text-green-800"><?php echo $appointmentStats[
                        "completed"
                    ]; ?></p>
                </div>
                <div class="p-3 bg-nhd-green/50 rounded-full">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Cancellation Requests -->
        <a href="<?php echo BASE_URL; ?>/doctor/appointment-history?filter=cancellation_requests" class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-700">Cancellation Requests</p>
                    <p class="text-3xl font-bold text-red-800"><?php echo $appointmentStats[
                        "cancellation_requests"
                    ]; ?></p>
                </div>
                <div class="p-3 bg-red-200/50 rounded-full">
                    <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Appointments -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Upcoming Appointments Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-semibold text-nhd-brown font-family-sans tracking-tight">Upcoming Appointments</h3>
                    <a href="<?php echo BASE_URL; ?>/doctor/schedule" 
                       class="inline-flex items-center px-4 py-2 text-sm bg-nhd-blue/80 glass-card text-white rounded-2xl hover:bg-nhd-blue transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        View Schedule
                    </a>
                </div>

                <?php if (!empty($upcomingAppointments)): ?>
                    <div class="space-y-4">
                        <?php foreach (
                            $upcomingAppointments as $appointment
                        ): ?>
                            <div class="glass-card bg-neutral-100/50 rounded-2xl shadow-sm p-6 border-1 border-gray-200 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900">
                                            <?php echo htmlspecialchars(
                                                $appointment["AppointmentType"]
                                            ); ?>
                                        </h4>
                                        <p class="text-gray-600 mt-1">
                                            Patient: <?php echo htmlspecialchars(
                                                $appointment[
                                                    "PatientFirstName"
                                                ] .
                                                    " " .
                                                    $appointment[
                                                        "PatientLastName"
                                                    ]
                                            ); ?>
                                        </p>
                                        <p class="text-sm text-gray-500 mt-2">
                                            Reason: <?php echo htmlspecialchars(
                                                $appointment["Reason"]
                                            ); ?>
                                        </p>
                                        
                                        <!-- Status Badge -->
                                        <div class="mt-3">
                                            <span class="inline-block px-3 py-1 text-xs font-medium rounded-full 
                                                <?php echo strtolower(
                                                    $appointment["Status"]
                                                ) === "confirmed"
                                                    ? "bg-green-100 text-green-800"
                                                    : "bg-yellow-100 text-yellow-800"; ?>">
                                                <?php echo htmlspecialchars(
                                                    $appointment["Status"]
                                                ); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-nhd-blue">
                                            <?php echo date(
                                                "M j, Y",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </p>
                                        <p class="text-gray-600">
                                            <?php echo date(
                                                "g:i A",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </p>
                                        <a href="<?php echo BASE_URL; ?>/doctor/schedule" 
                                           class="inline-flex items-center glass-card mt-2 px-3 py-1 text-sm bg-gray-200/80 text-black rounded-2xl shadow-sm border-1 border-gray-200 hover:bg-gray-200 transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($upcomingAppointments) > 5): ?>
                            <div class="text-center pt-4">
                                <a href="<?php echo BASE_URL; ?>/doctor/schedule" 
                                   class="text-nhd-blue hover:text-nhd-blue/80 font-medium">
                                    View all <?php echo count(
                                        $upcomingAppointments
                                    ); ?> upcoming appointments →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="bg-gray-50/50 glass-card rounded-2xl p-8 text-center shadow-none border-gray-200 border-1">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500 mb-4">No upcoming appointments scheduled</p>
                        <a href="<?php echo BASE_URL; ?>/doctor/schedule" 
                           class="inline-flex items-center px-4 py-2 bg-nhd-blue/80 glass-card text-white rounded-2xl hover:bg-nhd-blue transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            View Schedule
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recently Completed Appointments Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-nhd-brown font-family-sans tracking-tight">Recently Completed</h3>
                    <a href="<?php echo BASE_URL; ?>/doctor/appointment-history" class="text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                        View History →
                    </a>
                </div>

                <?php if (!empty($recentlyCompletedAppointments)): ?>
                    <div class="space-y-4">
                        <?php foreach (
                            $recentlyCompletedAppointments as $appointment
                        ): ?>
                            <div class="glass-card bg-white/60 rounded-xl p-5 border border-gray-200 shadow-sm">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?></h4>
                                        <p class="text-gray-600 text-sm">
                                            Patient: <?php echo htmlspecialchars(
                                                $appointment[
                                                    "PatientFirstName"
                                                ] .
                                                    " " .
                                                    $appointment[
                                                        "PatientLastName"
                                                    ]
                                            ); ?>
                                        </p>
                                        <p class="text-nhd-blue font-medium text-sm mt-1">
                                            Completed: <?php echo date(
                                                "M j, Y g:i A",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </p>
                                    </div>
                                    <span class="inline-block px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">
                                        Reason: <span class="font-medium"><?php echo htmlspecialchars(
                                            $appointment["Reason"]
                                        ); ?></span>
                                    </span>
                                    <a href="<?php echo BASE_URL; ?>/doctor/appointment-history" class="glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl font-medium hover:bg-nhd-blue transition-colors">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 mb-2">No completed appointments yet</p>
                        <p class="text-gray-400 text-sm">Completed appointments will appear here</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Quick Info & Week View -->
        <div class="space-y-8">
            <!-- Cancellation Requests -->
            <?php if (!empty($pendingCancellations)): ?>
                <div class="glass-card rounded-2xl p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-nhd-brown">Cancellation Requests</h3>
                        <a href="<?php echo BASE_URL; ?>/doctor/appointment-history" class="text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                            View All
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        <?php foreach (
                            $pendingCancellations as $cancellation
                        ): ?>
                            <div class="flex items-center justify-between p-3 bg-red-50/50 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars(
                                        $cancellation["AppointmentType"]
                                    ); ?></p>
                                    <p class="text-sm text-gray-600">
                                        Patient: <?php echo htmlspecialchars(
                                            $cancellation["PatientFirstName"] .
                                                " " .
                                                $cancellation["PatientLastName"]
                                        ); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Scheduled: <?php echo date(
                                            "M j, Y g:i A",
                                            strtotime($cancellation["DateTime"])
                                        ); ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        Needs Review
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center mt-4 pt-3 border-t border-red-200">
                        <a href="<?php echo BASE_URL; ?>/doctor/appointment-history" 
                           class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Review all requests →
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Doctor Information -->
            <div class="glass-card rounded-2xl p-6 border-1 border-gray-200 shadow-none">
                <h3 class="text-xl font-bold text-nhd-brown mb-4">Doctor Information</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-medium text-gray-900">
                            Dr. <?php echo htmlspecialchars(
                                $doctor["firstName"] . " " . $doctor["lastName"]
                            ); ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Specialization:</span>
                        <span class="font-medium text-gray-900"><?php echo htmlspecialchars(
                            $doctor["specialization"]
                        ); ?></span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium text-gray-900"><?php echo htmlspecialchars(
                            $doctor["email"]
                        ); ?></span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Patients:</span>
                        <span class="font-medium text-gray-900"><?php echo count(
                            $recentPatientVisits
                        ); ?>+</span>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200 text-center">
                    <a href="<?php echo BASE_URL; ?>/doctor/profile" 
                       class="text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                        Update Profile
                    </a>
                </div>
            </div>

            <!-- Week View -->
            <div class="glass-card rounded-2xl p-6 border-1 border-gray-200 shadow-none">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-nhd-brown">This Week</h3>
                    <span class="text-sm text-gray-500">
                        <?php echo date(
                            "M j",
                            strtotime($startOfWeek)
                        ); ?> - <?php echo date(
                            "M j",
                            strtotime($endOfWeek)
                        ); ?>
                    </span>
                </div>

                <div class="grid grid-cols-7 gap-1 mb-2">
                    <?php
                    $daysOfWeek = [
                        "Mon",
                        "Tue",
                        "Wed",
                        "Thu",
                        "Fri",
                        "Sat",
                        "Sun",
                    ];
            foreach ($daysOfWeek as $day): ?>
                        <div class="text-center text-xs font-medium text-gray-600 py-1"><?php echo $day; ?></div>
                    <?php endforeach;
            ?>
                </div>

                <div class="grid grid-cols-7 gap-1">
                    <?php for ($i = 0; $i < 7; $i++):

                        $currentDate = date(
                            "Y-m-d",
                            strtotime($startOfWeek . " +" . $i . " days")
                        );
                        $dayAppointments = array_filter(
                            $weekAppointments,
                            function ($app) use ($currentDate) {
                                return date(
                                    "Y-m-d",
                                    strtotime($app["DateTime"])
                                ) === $currentDate;
                            }
                        );
                        $isToday = $currentDate === date("Y-m-d");
                        ?>
                        <div class="text-center">
                            <div class="w-8 h-8 mx-auto mb-1 rounded-full flex items-center justify-center text-sm
                                <?php echo $isToday
                                    ? "bg-nhd-blue text-white font-bold"
                                    : "text-gray-600"; ?>">
                                <?php echo date(
                                    "j",
                                    strtotime($currentDate)
                                ); ?>
                            </div>
                            
                            <?php if (!empty($dayAppointments)): ?>
                                <div class="space-y-1">
                                    <?php foreach (
                                        array_slice($dayAppointments, 0, 2) as $appointment
                                    ): ?>
                                        <div class="w-full bg-nhd-blue/20 text-nhd-blue text-xs px-1 py-0.5 rounded">
                                            <?php echo date(
                                                "H:i",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <?php if (count($dayAppointments) > 2): ?>
                                        <div class="text-xs text-gray-500">+<?php echo count(
                                            $dayAppointments
                                        ) - 2; ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php
                    endfor; ?>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="<?php echo BASE_URL; ?>/doctor/schedule" 
                       class="block text-center text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                        View Full Schedule
                    </a>
                </div>
            </div>

            <!-- Recent Patient Visits -->
            <div class="glass-card rounded-2xl p-6 border-1 border-gray-200 shadow-none">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-nhd-brown">Recent Patient Visits</h3>
                    <a href="<?php echo BASE_URL; ?>/doctor/patient-records" class="text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                        View All
                    </a>
                </div>

                <?php if (!empty($recentPatientVisits)): ?>
                    <div class="space-y-3">
                        <?php foreach (
                            array_slice($recentPatientVisits, 0, 5) as $visit
                        ): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50/50 rounded-lg hover:bg-gray-100/50 transition-colors">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm">
                                        <?php echo htmlspecialchars(
                                            $visit["PatientFirstName"] .
                                                " " .
                                                $visit["PatientLastName"]
                                        ); ?>
                                    </p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <p class="text-xs text-gray-600">
                                            <?php echo date(
                                                "M j, Y",
                                                strtotime($visit["DateTime"])
                                            ); ?>
                                        </p>
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                            <?php echo htmlspecialchars(
                                                $visit["AppointmentType"]
                                            ); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500">
                                        <?php echo date(
                                            "g:i A",
                                            strtotime($visit["DateTime"])
                                        ); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($recentPatientVisits) > 5): ?>
                        <div class="text-center mt-4 pt-3 border-t border-gray-200">
                            <span class="text-sm text-gray-500">
                                Showing 5 of <?php echo count(
                                    $recentPatientVisits
                                ); ?> recent visits
                            </span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-6">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">No recent patient visits</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="<?php echo BASE_URL; ?>/doctor/schedule" 
           class="glass-card border-gray-200 border-2 rounded-2xl p-6 shadow-sm group">
            <div class="flex items-center">
                <div class="p-3 bg-nhd-blue/20 rounded-full mr-4 group-hover:bg-nhd-blue/30 transition-colors">
                    <svg class="w-6 h-6 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-nhd-blue">View Schedule</h4>
                    <p class="text-sm text-gray-600">Manage your appointments</p>
                </div>
            </div>
        </a>

        <a href="<?php echo BASE_URL; ?>/doctor/patient-records" 
           class="glass-card border-gray-200 border-2 rounded-2xl p-6 shadow-sm group">
            <div class="flex items-center">
                <div class="p-3 bg-green-200/50 rounded-full mr-4 group-hover:bg-green-300/50 transition-colors">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-green-800">Patient Records</h4>
                    <p class="text-sm text-gray-600">Access patient information</p>
                </div>
            </div>
        </a>

        <a href="<?php echo BASE_URL; ?>/doctor/appointment-history" 
           class="glass-card border-gray-200 border-2 rounded-2xl p-6 shadow-sm group">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-200/50 rounded-full mr-4 group-hover:bg-yellow-300/50 transition-colors">
                    <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-yellow-800">Appointment History</h4>
                    <p class="text-sm text-gray-600">Review past appointments</p>
                </div>
            </div>
        </a>
    </div>
</div>
