<div class="mx-auto px-4 pb-4">
    <!-- Header Section -->
    <div class="mb-8">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
            <?php echo $_SESSION["user_type"] . " Dashboard"; ?>
        </h2>
        <p class="text-gray-600 mb-4">
            Welcome back, <?php echo htmlspecialchars(
                $user["name"]
            ); ?>! Here's your comprehensive dental care overview.
        </p>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Appointments -->
        <div class="glass-card bg-nhd-blue/10 border-2 border-nhd-blue/60 rounded-2xl p-6 shadow-md">
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

        <!-- Completed Appointments -->
        <div class="glass-card bg-green-50/50 border-2 border-nhd-green rounded-2xl p-6 shadow-md">
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
        </div>

        <!-- Upcoming Appointments -->
        <div class="glass-card bg-yellow-50/50 border-2 border-nhd-pale rounded-2xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-700">Upcoming</p>
                    <p class="text-3xl font-bold text-yellow-800"><?php echo $appointmentStats[
                        "upcoming"
                    ]; ?></p>
                </div>
                <div class="p-3 bg-nhd-pale/50 rounded-full">
                    <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="glass-card bg-red-50/50 border-2 border-red-200 rounded-2xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-700">Pending Payments</p>
                    <p class="text-3xl font-bold text-red-800">$<?php echo number_format(
                        $totalPendingAmount,
                        2
                    ); ?></p>
                </div>
                <div class="p-3 bg-red-200/50 rounded-full">
                    <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Appointments & Treatments -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Upcoming Appointments Section -->
            <div class="glass-card rounded-2xl p-6 border-gray-200 border-1 shadow-none">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-nhd-brown font-family-sans">Upcoming Appointments</h3>
                    <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                       class="inline-flex items-center px-4 py-2 bg-nhd-blue/80 glass-card text-white rounded-2xl hover:bg-nhd-blue transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Book Appointment
                    </a>
                </div>

                <?php if (!empty($upcomingAppointments)): ?>
                    <div class="space-y-4">
                        <?php foreach (
                            array_slice($upcomingAppointments, 0, 3)
                            as $appointment
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
                                            with Dr. <?php echo htmlspecialchars(
                                                $appointment[
                                                    "DoctorFirstName"
                                                ] .
                                                    " " .
                                                    $appointment[
                                                        "DoctorLastName"
                                                    ]
                                            ); ?>
                                            - <?php echo htmlspecialchars(
                                                $appointment["Specialization"]
                                            ); ?>
                                        </p>
                                        <p class="text-sm text-gray-500 mt-2">
                                            Reason: <?php echo htmlspecialchars(
                                                $appointment["Reason"]
                                            ); ?>
                                        </p>
                                        
                                        <!-- Payment Status -->
                                        <div class="mt-3 flex items-center space-x-2">
                                            <?php if (
                                                isset(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]
                                                )
                                            ): ?>
                                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full 
                                                    <?php echo strtolower(
                                                        $appointmentPayments[
                                                            $appointment[
                                                                "AppointmentID"
                                                            ]
                                                        ]["Status"]
                                                    ) === "paid"
                                                        ? "bg-green-100 text-green-800"
                                                        : "bg-yellow-100 text-yellow-800"; ?>">
                                                    <?php echo htmlspecialchars(
                                                        $appointmentPayments[
                                                            $appointment[
                                                                "AppointmentID"
                                                            ]
                                                        ]["Status"]
                                                    ); ?>
                                                </span>
                                                <span class="text-nhd-brown font-semibold">
                                                    $<?php echo number_format(
                                                        $appointmentPayments[
                                                            $appointment[
                                                                "AppointmentID"
                                                            ]
                                                        ]["total_amount"] ?? 0,
                                                        2
                                                    ); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending Payment
                                                </span>
                                            <?php endif; ?>
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
                                        <a href="<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>" 
                                           class="inline-flex items-center glass-card mt-2 px-3 py-1 text-sm bg-nhd-blue/80 text-white rounded-lg hover:bg-nhd-blue transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($upcomingAppointments) > 3): ?>
                            <div class="text-center pt-4">
                                <a href="<?php echo BASE_URL; ?>/patient/bookings" 
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
                        <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                           class="inline-flex items-center px-4 py-2 bg-nhd-blue/80 glass-card text-white rounded-2xl hover:bg-nhd-blue transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Book Your First Appointment
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Current Treatments Section -->
            <div class="glass-card rounded-2xl p-6 border-1 border-gray-200 shadow-none">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-nhd-brown font-family-sans">Current Treatments</h3>
                    <span class="text-sm text-gray-500 px-3 py-1 bg-gray-100 rounded-full">Preview Mode</span>
                </div>

                <?php if (!empty($currentTreatments)): ?>
                    <div class="space-y-4">
                        <?php foreach ($currentTreatments as $treatment): ?>
                            <div class="glass-card bg-white/60 rounded-xl p-5 border border-gray-200 shadow-sm">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars(
                                            $treatment["name"]
                                        ); ?></h4>
                                        <p class="text-gray-600 text-sm"><?php echo htmlspecialchars(
                                            $treatment["description"]
                                        ); ?></p>
                                        <p class="text-nhd-blue font-medium text-sm mt-1">
                                            with <?php echo htmlspecialchars(
                                                $treatment["doctor"]
                                            ); ?> • <?php echo htmlspecialchars(
     $treatment["specialization"]
 ); ?>
                                        </p>
                                    </div>
                                    <span class="inline-block px-3 py-1 text-xs font-medium rounded-full 
                                        <?php echo $treatment["status"] ===
                                        "In Progress"
                                            ? "bg-blue-100 text-blue-800"
                                            : "bg-orange-100 text-orange-800"; ?>">
                                        <?php echo htmlspecialchars(
                                            $treatment["status"]
                                        ); ?>
                                    </span>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Progress</span>
                                        <span class="text-nhd-blue font-semibold"><?php echo $treatment[
                                            "progress"
                                        ]; ?>%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-nhd-blue to-nhd-blue/80 h-2 rounded-full transition-all duration-300" 
                                             style="width: <?php echo $treatment[
                                                 "progress"
                                             ]; ?>%"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">
                                        Next Appointment: <span class="font-medium"><?php echo date(
                                            "M j, Y",
                                            strtotime(
                                                $treatment["next_appointment"]
                                            )
                                        ); ?></span>
                                    </span>
                                    <button class="text-nhd-blue hover:text-nhd-blue/80 font-medium">View Details</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <p class="text-gray-500">No active treatments at the moment</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Quick Info & Week View -->
        <div class="space-y-8">
            <!-- Pending Payments Summary -->
            <?php if (!empty($pendingPayments)): ?>
                <div class="glass-card rounded-2xl p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-nhd-brown">Pending Payments</h3>
                        <a href="<?php echo BASE_URL; ?>/patient/payments" class="text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                            View All
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        <?php foreach (
                            array_slice($pendingPayments, 0, 3)
                            as $payment
                        ): ?>
                            <div class="flex items-center justify-between p-3 bg-yellow-50/50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars(
                                        $payment["AppointmentType"]
                                    ); ?></p>
                                    <p class="text-sm text-gray-600">
                                        <?php echo date(
                                            "M j, Y",
                                            strtotime(
                                                $payment["AppointmentDateTime"]
                                            )
                                        ); ?>
                                    </p>
                                </div>
                                <span class="font-bold text-yellow-800">$<?php echo number_format(
                                    $payment["total_amount"],
                                    2
                                ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($pendingPayments) > 3): ?>
                        <div class="text-center mt-4 pt-3 border-t border-yellow-200">
                            <span class="text-sm text-yellow-700">
                                +<?php echo count($pendingPayments) -
                                    3; ?> more pending payments
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Patient Physical Information -->
            <div class="glass-card rounded-2xl p-6 border-1 border-gray-200 shadow-none">
                <h3 class="text-xl font-bold text-nhd-brown mb-4">Patient Information</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                                                 <span class="text-gray-600">Name:</span>
                         <span class="font-medium text-gray-900">
                             <?php echo htmlspecialchars($user["name"]); ?>
                         </span>
                    </div>
                    
                    <?php if ($patientPhysicalInfo): ?>
                        <?php if (!empty($patientPhysicalInfo["Height"])): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Height:</span>
                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars(
                                    $patientPhysicalInfo["Height"]
                                ); ?> cm</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($patientPhysicalInfo["Weight"])): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Weight:</span>
                                <span class="font-medium text-gray-900"><?php echo htmlspecialchars(
                                    $patientPhysicalInfo["Weight"]
                                ); ?> kg</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($patientPhysicalInfo["Allergies"])): ?>
                            <div>
                                <span class="text-gray-600 block mb-1">Allergies:</span>
                                <span class="text-red-600 text-sm bg-red-50 px-2 py-1 rounded">
                                    <?php echo htmlspecialchars(
                                        $patientPhysicalInfo["Allergies"]
                                    ); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($patientPhysicalInfo["LastVisit"])): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Visit:</span>
                                <span class="font-medium text-gray-900">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime(
                                            $patientPhysicalInfo["LastVisit"]
                                        )
                                    ); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm mb-3">No physical information recorded</p>
                            <a href="<?php echo BASE_URL; ?>/patient/profile" 
                               class="text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                                Update Information
                            </a>
                        </div>
                    <?php endif; ?>
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
                                        array_slice($dayAppointments, 0, 2)
                                        as $appointment
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
                    <a href="<?php echo BASE_URL; ?>/patient/bookings" 
                       class="block text-center text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                        View Full Calendar
                    </a>
                </div>
            </div>

            <!-- Mini Payments Section -->
            <div class="glass-card rounded-2xl p-6 border-1 border-gray-200 shadow-none">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-nhd-brown">Recent Payments</h3>
                    <a href="<?php echo BASE_URL; ?>/patient/payments" class="text-nhd-blue hover:text-nhd-blue/80 text-sm font-medium">
                        View All
                    </a>
                </div>

                <?php
                $paymentsWithValues = [];
                $allPaymentsList = [];

                // Convert appointmentPayments (keyed by AppointmentID) to array
                if (!empty($appointmentPayments)) {
                    foreach (
                        $appointmentPayments
                        as $appointmentId => $payment
                    ) {
                        $allPaymentsList[] = $payment;
                        if (($payment["total_amount"] ?? 0) > 0) {
                            $paymentsWithValues[] = $payment;
                        }
                    }
                }

                // Also include pending payments if they're separate
                if (!empty($pendingPayments)) {
                    foreach ($pendingPayments as $pendingPayment) {
                        // Check if not already in the list
                        $exists = false;
                        foreach ($allPaymentsList as $existingPayment) {
                            if (
                                ($existingPayment["PaymentID"] ?? null) ===
                                    ($pendingPayment["PaymentID"] ?? null) &&
                                ($existingPayment["AppointmentID"] ?? null) ===
                                    ($pendingPayment["AppointmentID"] ?? null)
                            ) {
                                $exists = true;
                                break;
                            }
                        }
                        if (!$exists) {
                            $allPaymentsList[] = $pendingPayment;
                            if (($pendingPayment["total_amount"] ?? 0) > 0) {
                                $paymentsWithValues[] = $pendingPayment;
                            }
                        }
                    }
                }

                // If we still don't have payments, let's try to get them from all appointments with mock payments
                if (empty($allPaymentsList) && !empty($allAppointments)) {
                    foreach (
                        array_slice($allAppointments, 0, 5)
                        as $appointment
                    ) {
                        $allPaymentsList[] = [
                            "PaymentID" => null,
                            "AppointmentID" => $appointment["AppointmentID"],
                            "AppointmentType" =>
                                $appointment["AppointmentType"],
                            "AppointmentDateTime" => $appointment["DateTime"],
                            "Status" => "Pending",
                            "total_amount" => 0.0,
                            "DoctorName" =>
                                ($appointment["DoctorFirstName"] ?? "") .
                                " " .
                                ($appointment["DoctorLastName"] ?? ""),
                        ];
                    }
                }

                // Sort by date (most recent first)
                usort($allPaymentsList, function ($a, $b) {
                    $dateA =
                        $a["AppointmentDateTime"] ??
                        ($a["UpdatedAt"] ?? "1970-01-01");
                    $dateB =
                        $b["AppointmentDateTime"] ??
                        ($b["UpdatedAt"] ?? "1970-01-01");
                    return strtotime($dateB) - strtotime($dateA);
                });
                usort($paymentsWithValues, function ($a, $b) {
                    $dateA =
                        $a["AppointmentDateTime"] ??
                        ($a["UpdatedAt"] ?? "1970-01-01");
                    $dateB =
                        $b["AppointmentDateTime"] ??
                        ($b["UpdatedAt"] ?? "1970-01-01");
                    return strtotime($dateB) - strtotime($dateA);
                });

                // Use payments with values if available, otherwise use latest 5
                $displayPayments = !empty($paymentsWithValues)
                    ? array_slice($paymentsWithValues, 0, 5)
                    : array_slice($allPaymentsList, 0, 5);
                ?>

                <?php if (!empty($displayPayments)): ?>
                    <div class="space-y-3">
                        <?php foreach ($displayPayments as $payment): ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50/50 rounded-lg hover:bg-gray-100/50 transition-colors">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 text-sm"><?php echo htmlspecialchars(
                                        $payment["AppointmentType"]
                                    ); ?></p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <p class="text-xs text-gray-600">
                                            <?php echo date(
                                                "M j, Y",
                                                strtotime(
                                                    $payment[
                                                        "AppointmentDateTime"
                                                    ]
                                                )
                                            ); ?>
                                        </p>
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full 
                                            <?php echo strtolower(
                                                $payment["Status"]
                                            ) === "paid"
                                                ? "bg-green-100 text-green-700"
                                                : "bg-yellow-100 text-yellow-700"; ?>">
                                            <?php echo htmlspecialchars(
                                                $payment["Status"]
                                            ); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-nhd-brown">
                                        $<?php echo number_format(
                                            $payment["total_amount"],
                                            2
                                        ); ?>
                                    </span>
                                    <?php if ($payment["PaymentID"]): ?>
                                        <p class="text-xs text-gray-500 mt-1">
                                            #<?php echo str_pad(
                                                $payment["PaymentID"],
                                                6,
                                                "0",
                                                STR_PAD_LEFT
                                            ); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($allPaymentsList) > 5): ?>
                        <div class="text-center mt-4 pt-3 border-t border-gray-200">
                            <span class="text-sm text-gray-500">
                                Showing <?php echo count(
                                    $displayPayments
                                ); ?> of <?php echo count(
     $allPaymentsList
 ); ?> payments
                            </span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-6">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">No payment records found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
           class="glass-card bg-nhd-blue/10 border-2 border-nhd-blue/20 rounded-2xl p-6 hover:bg-nhd-blue/15 transition-all group shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-nhd-blue/20 rounded-full mr-4 group-hover:bg-nhd-blue/30 transition-colors">
                    <svg class="w-6 h-6 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-nhd-blue">Book Appointment</h4>
                    <p class="text-sm text-gray-600">Schedule your next visit</p>
                </div>
            </div>
        </a>

        <a href="<?php echo BASE_URL; ?>/patient/payments" 
           class="glass-card bg-green-50/50 border-2 border-green-200 rounded-2xl p-6 hover:bg-green-100/50 transition-all group shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-green-200/50 rounded-full mr-4 group-hover:bg-green-300/50 transition-colors">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-green-800">View Payments</h4>
                    <p class="text-sm text-gray-600">Manage your billing</p>
                </div>
            </div>
        </a>

        <a href="<?php echo BASE_URL; ?>/patient/profile" 
           class="glass-card bg-yellow-50/50 border-2 border-yellow-200 rounded-2xl p-6 hover:bg-yellow-100/50 transition-all group shadow-sm">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-200/50 rounded-full mr-4 group-hover:bg-yellow-300/50 transition-colors">
                    <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-yellow-800">Update Profile</h4>
                    <p class="text-sm text-gray-600">Manage your information</p>
                </div>
            </div>
        </a>
    </div>
</div> 