<div class="pb-8">
    <div class="px-4">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">My Bookings</h2>
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
                    <div class="glass-card rounded-2xl shadow-md border-1 border-gray-400/70 hover:border-green-600 p-6 cursor-pointer transition-all duration-300 hover:shadow-lg hover:scale-[1.01] group"
                         onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')">
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="glass-card text-nhd-blue border-nhd-blue/20 border-1 px-3 py-2 rounded-2xl">
                                <span class="text-xs font-medium uppercase tracking-wider block">Appointment ID</span>
                                <span class="text-lg font-bold font-mono">#<?php echo str_pad(
                                    $appointment["AppointmentID"],
                                    6,
                                    "0",
                                    STR_PAD_LEFT
                                ); ?></span>
                            </div>
                            <span class="glass-card px-2 py-1 text-xs font-medium rounded-full bg-green-100/40 text-green-800 group-hover:bg-green-200/60 transition-colors">
                                Upcoming
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 mr-3 group-hover:text-nhd-blue transition-colors">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </h3>
                                </div>
                                <p class="text-gray-600 mb-1 group-hover:text-gray-700 transition-colors">
                                    <strong>Doctor:</strong> Dr. <?php echo htmlspecialchars(
                                        $appointment["DoctorFirstName"] .
                                            " " .
                                            $appointment["DoctorLastName"]
                                    ); ?>
                                    <span class="text-gray-500 group-hover:text-gray-600 transition-colors"> - <?php echo htmlspecialchars(
                                        $appointment["Specialization"]
                                    ); ?></span>
                                </p>
                                <p class="text-xs text-gray-500 group-hover:text-gray-600 transition-colors">
                                    Booked on: <?php echo date(
                                        "M j, Y g:i A",
                                        strtotime($appointment["CreatedAt"])
                                    ); ?>
                                </p>
                            </div>
                            <div class="text-right ml-6">
                                <p class="text-lg font-semibold text-green-600 group-hover:text-green-700 transition-colors">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </p>
                                <p class="text-gray-600 group-hover:text-gray-700 transition-colors">
                                    <?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </p>
                                <div class="mt-2 opacity-75 group-hover:opacity-100 transition-opacity">
                                    <span class="glass-card text-sm px-3 py-1 bg-green-100/60 text-green-700 rounded-2xl">
                                        Click to Manage
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Information at Bottom -->
                        <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="glass-card bg-nhd-brown/10 text-nhd-brown px-2 py-1 rounded text-xs">
                                    <span class="font-medium">Payment ID:</span> 
                                    <span class="font-mono font-bold"><?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>#<?php echo str_pad(
    $appointmentPayments[$appointment["AppointmentID"]]["PaymentID"],
    6,
    "0",
    STR_PAD_LEFT
);else: ?>-<?php endif; ?></span>
                                </div>
                                <div class="text-xs font-medium px-2 py-1 rounded-full 
                                    <?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>
                                        <?php $payment =
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]; ?>
                                        <?php echo strtolower(
                                            $payment["Status"]
                                        ) === "paid"
                                            ? "bg-green-100 text-green-800"
                                            : "bg-yellow-100 text-yellow-800"; ?>">
                                        <?php echo htmlspecialchars(
                                            $payment["Status"]
                                        ); ?>
                                    <?php else: ?>
                                        bg-yellow-100 text-yellow-800">
                                        Pending
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-nhd-brown">
                                $<?php if (
                                    isset(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]
                                    )
                                ):
                                    echo number_format(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]["total_amount"] ?? 0,
                                        2
                                    );
                                else:
                                     ?>0.00<?php
                                endif; ?>
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
                    <div class="glass-card rounded-2xl shadow-md border-l-4 border-l-gray-400/70 hover:border-l-gray-500 p-6 cursor-pointer transition-all duration-300 hover:shadow-lg hover:scale-[1.01] group"
                         onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')">
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="glass-card text-nhd-blue border-nhd-blue/20 border-1 px-3 py-2 rounded-2xl">
                                <span class="text-xs font-medium uppercase tracking-wider block">Appointment ID</span>
                                <span class="text-lg font-bold font-mono">#<?php echo str_pad(
                                    $appointment["AppointmentID"],
                                    6,
                                    "0",
                                    STR_PAD_LEFT
                                ); ?></span>
                            </div>
                            <span class="glass-card px-2 py-1 text-xs font-medium rounded-full bg-gray-100/40 text-gray-800 group-hover:bg-gray-200/60 transition-colors">
                                Completed
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 mr-3 group-hover:text-nhd-brown transition-colors">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </h3>
                                </div>
                                <p class="text-gray-600 mb-1 group-hover:text-gray-700 transition-colors">
                                    <strong>Doctor:</strong> Dr. <?php echo htmlspecialchars(
                                        $appointment["DoctorFirstName"] .
                                            " " .
                                            $appointment["DoctorLastName"]
                                    ); ?>
                                    <span class="text-gray-500 group-hover:text-gray-600 transition-colors"> - <?php echo htmlspecialchars(
                                        $appointment["Specialization"]
                                    ); ?></span>
                                </p>
                                <p class="text-sm text-gray-600 mb-2 group-hover:text-gray-700 transition-colors">
                                    <strong>Reason:</strong> <?php echo htmlspecialchars(
                                        $appointment["Reason"]
                                    ); ?>
                                </p>
                                <p class="text-xs text-gray-500 group-hover:text-gray-600 transition-colors">
                                    Booked on: <?php echo date(
                                        "M j, Y g:i A",
                                        strtotime($appointment["CreatedAt"])
                                    ); ?>
                                </p>
                            </div>
                            <div class="text-right ml-6">
                                <p class="text-lg font-semibold text-gray-600 group-hover:text-gray-700 transition-colors">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </p>
                                <p class="text-gray-600 group-hover:text-gray-700 transition-colors">
                                    <?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </p>
                                <div class="mt-2 opacity-75 group-hover:opacity-100 transition-opacity">
                                    <span class="glass-card text-sm px-3 py-1 bg-blue-100/60 text-blue-700 rounded-2xl">
                                        Click to View Report
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Information at Bottom -->
                        <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="glass-card bg-nhd-brown/10 text-nhd-brown px-2 py-1 rounded text-xs">
                                    <span class="font-medium">Payment ID:</span> 
                                    <span class="font-mono font-bold"><?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>#<?php echo str_pad(
    $appointmentPayments[$appointment["AppointmentID"]]["PaymentID"],
    6,
    "0",
    STR_PAD_LEFT
);else: ?>-<?php endif; ?></span>
                                </div>
                                <div class="text-xs font-medium px-2 py-1 rounded-full 
                                    <?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>
                                        <?php $payment =
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]; ?>
                                        <?php echo strtolower(
                                            $payment["Status"]
                                        ) === "paid"
                                            ? "bg-green-100 text-green-800"
                                            : "bg-yellow-100 text-yellow-800"; ?>">
                                        <?php echo htmlspecialchars(
                                            $payment["Status"]
                                        ); ?>
                                    <?php else: ?>
                                        bg-yellow-100 text-yellow-800">
                                        Pending
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-nhd-brown">
                                $<?php if (
                                    isset(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]
                                    )
                                ):
                                    echo number_format(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]["total_amount"] ?? 0,
                                        2
                                    );
                                else:
                                     ?>0.00<?php
                                endif; ?>
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

// Function to navigate to appointment detail page
function navigateToAppointment(url) {
    window.location.href = url;
}
</script>

