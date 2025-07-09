<?php
// Helper function to get status styling
function getAppointmentStatusClass($status)
{
    switch (strtolower($status)) {
        case "pending":
            return "bg-yellow-100/60 text-yellow-800";
        case "approved":
            return "bg-green-100/60 text-green-800";
        case "rescheduled":
            return "bg-blue-100/60 text-blue-800";
        case "completed":
            return "bg-gray-100/60 text-gray-800";
        case "declined":
            return "bg-red-100/60 text-red-800";
        case "cancelled":
            return "bg-orange-100/60 text-orange-800";
        case "pending cancellation":
            return "bg-purple-100/60 text-purple-800";
        default:
            return "bg-gray-100/60 text-gray-600";
    }
} ?>

<div class="pb-8">
    <div class="px-4">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">My Bookings</h2>
        <p class="text-gray-600">View and manage all your dental appointments.</p>
    </div> 
    <div class="flex justify-between items-center mb-4 p-4">
        <div class="flex space-x-2">
            <button onclick="showSection('all')" id="all-btn" class="glass-card bg-nhd-blue/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-white">
                All Appointments
            </button>
            <button onclick="showSection('upcoming')" id="upcoming-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Upcoming
            </button>
            <button onclick="showSection('completed')" id="completed-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Completed
            </button>
            <button onclick="showSection('cancelled')" id="cancelled-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Cancelled
            </button>
            <button onclick="showSection('pending-cancellation')" id="pending-cancellation-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Pending Cancellation
            </button>
        </div>
        <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
           class="inline-flex items-center px-4 py-2 glass-card bg-green-700/85 text-white text-sm rounded-2xl hover:bg-green-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Book New Appointment
        </a>
    </div>

    <!-- Upcoming Appointments Section -->
    <div id="upcoming-section" class="appointment-section">
        <div class="px-3 mb-3">
            <h3 class="text-2xl font-semibold text-nhd-brown">Upcoming Appointments</h3>
            <p class="text-gray-600 text-sm">Appointments you have scheduled</p>
        </div>
        <?php if (!empty($upcomingAppointments)): ?>
            <!-- Mobile View -->
            <div class="block lg:hidden">
                <?php foreach ($upcomingAppointments as $appointment): ?>
                    <div class="p-4 border-b border-gray-200/30 hover:bg-white/40 transition-colors rounded-2xl glass-card mb-3">
                        <div class="flex justify-between items-start mb-2">
                            <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded text-xs font-medium">
                                #<?php echo str_pad(
                                    $appointment["AppointmentID"],
                                    6,
                                    "0",
                                    STR_PAD_LEFT
                                ); ?>
                                &nbsp;|&nbsp;
                                <?php echo date(
                                    "M j",
                                    strtotime($appointment["DateTime"])
                                ); ?> • <?php echo date(
                                    "g:i A",
                                    strtotime($appointment["DateTime"])
                                ); ?>
                            </div>
                            <span class="<?php echo getAppointmentStatusClass(
                                $appointment["Status"]
                            ); ?> px-2 py-1 rounded-full text-xs"><?php echo $appointment[
     "Status"
 ]; ?></span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">
                            Dr. <?php echo htmlspecialchars(
                                $appointment["DoctorFirstName"] .
                                    " " .
                                    $appointment["DoctorLastName"]
                            ); ?>
                        </h4>
                        <div class="text-sm text-gray-600 mb-2">
                            <?php echo htmlspecialchars(
                                $appointment["Specialization"]
                            ); ?>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-sm">
                                <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                    <?php echo htmlspecialchars(
                                        $appointment["AppointmentType"]
                                    ); ?>
                                </span>
                            </div>
                            <div class="flex space-x-1">
                                <?php if (
                                    isset(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]
                                    )
                                ): ?>
                                    <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                                            class="bg-gray-500/80 text-white px-2 py-1 rounded text-xs hover:bg-gray-600 transition-colors">
                                        View Details
                                    </button>
                                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">No Cancellation</span>
                                <?php else: ?>
                                    <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                                            class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                        Manage
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-xs mt-2">
                            <div>
                                <span class="font-medium">Payment:</span> 
                                <?php if (
                                    isset(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]
                                    )
                                ): ?>
                                    <span class="font-mono">#<?php echo str_pad(
                                        $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["PaymentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?></span>
                                    <span class="<?php echo strtolower(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]["Status"]
                                    ) === "paid"
                                            ? "bg-green-100 text-green-800"
                                            : "bg-yellow-100 text-yellow-800 text-xs"; ?> px-2 py-1 rounded-full ml-1">
                                        <?php echo htmlspecialchars(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["Status"]
                                        ); ?>
                                    </span>
                                    <span class="ml-2 text-nhd-brown font-semibold">₱
                                        <?php echo number_format(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["total_amount"] ?? 0,
                                            2
                                        ); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-xl">Pending</span>
                                    <span class="ml-2 text-nhd-brown font-semibold">₱0.00</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto px-2">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-300 bg-gray-50/50">
                            <th class="text-left py-2 px-2 font-medium text-gray-700 text-xs w-24">Appointment ID</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Date & Time</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Doctor</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Type</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Status</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Payment</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/30">
                        <?php foreach (
                            $upcomingAppointments as $appointment
                        ): ?>
                            <tr class="hover:bg-nhd-blue/10 cursor-pointer transition-colors duration-200 border-b-1 border-gray-200"
                                onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')"
                                tabindex="0" role="button"
                                onkeydown="if(event.key==='Enter'||event.key===' '){navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')}">
                                <td class="py-2 px-2 font-mono text-xs text-gray-700 w-24 font-bold">
                                    #<?php echo str_pad(
                                        $appointment["AppointmentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                                        <div class="font-medium">
                                            <?php echo date(
                                                "M j",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                        <div class="font-bold">
                                            <?php echo date(
                                                "g:i A",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="font-medium text-gray-900 text-sm">
                                        Dr. <?php echo htmlspecialchars(
                                            $appointment["DoctorFirstName"] .
                                                " " .
                                                $appointment["DoctorLastName"]
                                        ); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo htmlspecialchars(
                                            $appointment["Specialization"]
                                        ); ?>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-3">
                                <span class="<?php echo getAppointmentStatusClass(
                                    $appointment["Status"]
                                ); ?> px-2 py-1 rounded-full text-xs"><?php echo $appointment[
     "Status"
 ]; ?></span>
                                </td>
                                <td class="py-2 px-3">
                                    <?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>
                                        <div class="flex flex-row gap-1">
                                            <span class="<?php echo strtolower(
                                                $appointmentPayments[
                                                    $appointment[
                                                        "AppointmentID"
                                                    ]
                                                ]["Status"]
                                            ) === "paid"
                                                ? "bg-green-100 text-green-800"
                                                : "bg-yellow-100 text-yellow-800 text-xs"; ?> px-2 py-1 rounded-full">
                                                <?php echo htmlspecialchars(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]["Status"]
                                                ); ?>
                                            </span>
                                            <span class="text-nhd-brown font-semibold">₱
                                                <?php echo number_format(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]["total_amount"] ?? 0,
                                                    2
                                                ); ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-xl">Pending</span>
                                        <span class="text-nhd-brown font-semibold ml-2">₱0.00</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-3">
                                    <?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>
                                        <div class="flex items-center space-x-1">
                                            <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                                                    class="bg-gray-500/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-gray-600 transition-colors">
                                                View Details
                                            </button>
                                            <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-xl text-xs">No Cancellation</span>
                                        </div>
                                    <?php else: ?>
                                        <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                                                class="bg-nhd-blue/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-nhd-blue transition-colors">
                                            Manage
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4 shadow-none border-1 border-gray-200">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">No upcoming appointments</p>
                <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                   class="inline-flex items-center px-4 py-2 glass-card bg-nhd-blue/80 text-white rounded-2xl hover:bg-nhd-blue transition-colors">
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
        <div class="px-3 mb-3 mt-8">
            <h3 class="text-2xl font-semibold text-nhd-brown">Completed Appointments</h3>
            <p class="text-gray-600 text-sm">Your appointment history</p>
        </div>
        <?php if (!empty($completedAppointments)): ?>
            <div class="block lg:hidden">
                <?php foreach ($completedAppointments as $appointment): ?>
                    <div class="p-4 border-b-1 border-gray-300 hover:bg-white/40 transition-colors rounded-2xl glass-card mb-3">
                        <div class="flex justify-between items-start mb-2">
                            <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded text-xs font-medium">
                                #<?php echo str_pad(
                                    $appointment["AppointmentID"],
                                    6,
                                    "0",
                                    STR_PAD_LEFT
                                ); ?>
                                &nbsp;|&nbsp;
                                <?php echo date(
                                    "M j",
                                    strtotime($appointment["DateTime"])
                                ); ?> • <?php echo date(
                                    "g:i A",
                                    strtotime($appointment["DateTime"])
                                ); ?>
                            </div>
                            <span class="<?php echo getAppointmentStatusClass(
                                $appointment["Status"]
                            ); ?> px-2 py-1 rounded-full text-xs"><?php echo $appointment[
     "Status"
 ]; ?></span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">
                            Dr. <?php echo htmlspecialchars(
                                $appointment["DoctorFirstName"] .
                                    " " .
                                    $appointment["DoctorLastName"]
                            ); ?>
                        </h4>
                        <div class="text-sm text-gray-600 mb-2">
                            <?php echo htmlspecialchars(
                                $appointment["Specialization"]
                            ); ?>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-sm">
                                <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                    <?php echo htmlspecialchars(
                                        $appointment["AppointmentType"]
                                    ); ?>
                                </span>
                            </div>
                            <div class="flex space-x-1">
                                <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
class="bg-nhd-blue/80 text-white px-2 py-1 text-xs hover:bg-nhd-blue transition-colors rounded-2xl">
                                    View Report
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-xs mt-2">
                            <div>
                                <span class="font-medium">Payment:</span> 
                                <?php if (
                                    isset(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]
                                    )
                                ): ?>
                                    <span class="font-mono">#<?php echo str_pad(
                                        $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["PaymentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?></span>
                                    <span class="<?php echo strtolower(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]["Status"]
                                    ) === "paid"
                                            ? "bg-green-100 text-green-800"
                                            : "bg-yellow-100 text-yellow-800 text-xs"; ?> px-2 py-1 rounded-full ml-1">
                                        <?php echo htmlspecialchars(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["Status"]
                                        ); ?>
                                    </span>
                                    <span class="ml-2 text-nhd-brown font-semibold">₱
                                        <?php echo number_format(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["total_amount"] ?? 0,
                                            2
                                        ); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-xl">Pending</span>
                                    <span class="ml-2 text-nhd-brown font-semibold">₱0.00</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto px-2">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-300 bg-gray-50/50">
                            <th class="text-left py-2 px-2 font-medium text-gray-700 text-xs w-24">Appointment ID</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Date & Time</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Doctor</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Type</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Status</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Payment</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/30">
                        <?php foreach (
                            $completedAppointments as $appointment
                        ): ?>
                            <tr class="hover:bg-nhd-blue/10 cursor-pointer transition-colors duration-200 border-b-1 border-gray-200"
                                onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')"
                                tabindex="0" role="button"
                                onkeydown="if(event.key==='Enter'||event.key===' '){navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')}">
                                <td class="py-2 px-2 font-mono text-xs text-gray-700 w-24 font-bold">
                                    #<?php echo str_pad(
                                        $appointment["AppointmentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                                        <div class="font-medium">
                                            <?php echo date(
                                                "M j",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                        <div class="font-bold">
                                            <?php echo date(
                                                "g:i A",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="font-medium text-gray-900 text-sm">
                                        Dr. <?php echo htmlspecialchars(
                                            $appointment["DoctorFirstName"] .
                                                " " .
                                                $appointment["DoctorLastName"]
                                        ); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo htmlspecialchars(
                                            $appointment["Specialization"]
                                        ); ?>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-3">
                                <span class="<?php echo getAppointmentStatusClass(
                                    $appointment["Status"]
                                ); ?> px-2 py-1 rounded-full text-xs"><?php echo $appointment[
     "Status"
 ]; ?></span>
                                </td>
                                <td class="py-2 px-3">
                                    <?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>
                                        <div class="flex flex-row gap-1">
                                            <span class="<?php echo strtolower(
                                                $appointmentPayments[
                                                    $appointment[
                                                        "AppointmentID"
                                                    ]
                                                ]["Status"]
                                            ) === "paid"
                                                ? "bg-green-100 text-green-800"
                                                : "bg-yellow-100 text-yellow-800 text-xs"; ?> px-2 py-1 rounded-full">
                                                <?php echo htmlspecialchars(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]["Status"]
                                                ); ?>
                                            </span>
                                            <span class="text-nhd-brown font-semibold">₱
                                                <?php echo number_format(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]["total_amount"] ?? 0,
                                                    2
                                                ); ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-xl">Pending</span>
                                        <span class="text-nhd-brown font-semibold ml-2">₱0.00</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-3">
                                    <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                                            class="bg-nhd-blue/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-nhd-blue transition-colors">
                                        View Report
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4 shadow-none border-1 border-gray-200">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">No completed appointments yet</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Cancelled Appointments Section -->
    <div id="cancelled-section" class="appointment-section">
        <div class="px-3 mb-3 mt-8">
            <h3 class="text-2xl font-semibold text-nhd-brown">Cancelled Appointments</h3>
            <p class="text-gray-600 text-sm">Appointments that have been cancelled</p>
        </div>
        <?php if (!empty($cancelledAppointments)): ?>
            <div class="block lg:hidden">
                <?php foreach ($cancelledAppointments as $appointment): ?>
                    <div class="p-4 border-b-1 border-gray-300 hover:bg-white/40 transition-colors rounded-2xl glass-card mb-3">
                        <div class="flex justify-between items-start mb-2">
                            <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded text-xs font-medium">
                                #<?php echo str_pad(
                                    $appointment["AppointmentID"],
                                    6,
                                    "0",
                                    STR_PAD_LEFT
                                ); ?>
                                &nbsp;|&nbsp;
                                <?php echo date(
                                    "M j",
                                    strtotime($appointment["DateTime"])
                                ); ?> • <?php echo date(
                                    "g:i A",
                                    strtotime($appointment["DateTime"])
                                ); ?>
                            </div>
                            <span class="<?php echo getAppointmentStatusClass(
                                $appointment["Status"]
                            ); ?> px-2 py-1 rounded-full text-xs"><?php echo $appointment[
     "Status"
 ]; ?></span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">
                            Dr. <?php echo htmlspecialchars(
                                $appointment["DoctorFirstName"] .
                                    " " .
                                    $appointment["DoctorLastName"]
                            ); ?>
                        </h4>
                        <div class="text-sm text-gray-600 mb-2">
                            <?php echo htmlspecialchars(
                                $appointment["Specialization"]
                            ); ?>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-sm">
                                <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                    <?php echo htmlspecialchars(
                                        $appointment["AppointmentType"]
                                    ); ?>
                                </span>
                            </div>
                            <div class="flex space-x-1">
                                <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
class="bg-gray-500/80 text-white px-2 py-1 text-xs hover:bg-gray-600 transition-colors rounded-2xl">
                                    View Details
                                </button>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-xs mt-2">
                            <div>
                                <span class="font-medium">Payment:</span> 
                                <?php if (
                                    isset(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]
                                    )
                                ): ?>
                                    <span class="font-mono">#<?php echo str_pad(
                                        $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["PaymentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?></span>
                                    <span class="<?php echo strtolower(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]["Status"]
                                    ) === "paid"
                                            ? "bg-green-100 text-green-800"
                                            : "bg-yellow-100 text-yellow-800 text-xs"; ?> px-2 py-1 rounded-full ml-1">
                                        <?php echo htmlspecialchars(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["Status"]
                                        ); ?>
                                    </span>
                                    <span class="ml-2 text-nhd-brown font-semibold">₱
                                        <?php echo number_format(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["total_amount"] ?? 0,
                                            2
                                        ); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-xl">N/A</span>
                                    <span class="ml-2 text-gray-500 font-semibold">₱0.00</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto px-2">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-300 bg-gray-50/50">
                            <th class="text-left py-2 px-2 font-medium text-gray-700 text-xs w-24">Appointment ID</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Date & Time</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Doctor</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Type</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Status</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Payment</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/30">
                        <?php foreach (
                            $cancelledAppointments as $appointment
                        ): ?>
                            <tr class="hover:bg-nhd-blue/10 cursor-pointer transition-colors duration-200 border-b-1 border-gray-200"
                                onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')"
                                tabindex="0" role="button"
                                onkeydown="if(event.key==='Enter'||event.key===' '){navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')}">
                                <td class="py-2 px-2 font-mono text-xs text-gray-700 w-24 font-bold">
                                    #<?php echo str_pad(
                                        $appointment["AppointmentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                                        <div class="font-medium">
                                            <?php echo date(
                                                "M j",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                        <div class="font-bold">
                                            <?php echo date(
                                                "g:i A",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="font-medium text-gray-900 text-sm">
                                        Dr. <?php echo htmlspecialchars(
                                            $appointment["DoctorFirstName"] .
                                                " " .
                                                $appointment["DoctorLastName"]
                                        ); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo htmlspecialchars(
                                            $appointment["Specialization"]
                                        ); ?>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-3">
                                <span class="<?php echo getAppointmentStatusClass(
                                    $appointment["Status"]
                                ); ?> px-2 py-1 rounded-full text-xs"><?php echo $appointment[
     "Status"
 ]; ?></span>
                                </td>
                                <td class="py-2 px-3">
                                    <?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>
                                        <div class="flex flex-row gap-1">
                                            <span class="<?php echo strtolower(
                                                $appointmentPayments[
                                                    $appointment[
                                                        "AppointmentID"
                                                    ]
                                                ]["Status"]
                                            ) === "paid"
                                                ? "bg-green-100 text-green-800"
                                                : "bg-yellow-100 text-yellow-800 text-xs"; ?> px-2 py-1 rounded-full">
                                                <?php echo htmlspecialchars(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]["Status"]
                                                ); ?>
                                            </span>
                                            <span class="text-nhd-brown font-semibold">₱
                                                <?php echo number_format(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]["total_amount"] ?? 0,
                                                    2
                                                ); ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-xl">N/A</span>
                                        <span class="text-gray-500 font-semibold ml-2">₱0.00</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-3">
                                    <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                                            class="bg-gray-500/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-gray-600 transition-colors">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4 shadow-none border-1 border-gray-200">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                </svg>
                <p class="text-gray-500">No cancelled appointments</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pending Cancellation Appointments Section -->
    <div id="pending-cancellation-section" class="appointment-section">
        <div class="px-3 mb-3 mt-8">
            <h3 class="text-2xl font-semibold text-nhd-brown">Pending Cancellation</h3>
            <p class="text-gray-600 text-sm">Appointments awaiting cancellation approval from doctor</p>
        </div>
        <?php if (!empty($pendingCancellationAppointments)): ?>
            <div class="block lg:hidden">
                <?php foreach (
                    $pendingCancellationAppointments as $appointment
                ): ?>
                    <div class="p-4 border-b-1 border-gray-300 hover:bg-white/40 transition-colors rounded-2xl glass-card mb-3">
                        <div class="flex justify-between items-start mb-2">
                            <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded text-xs font-medium">
                                #<?php echo str_pad(
                                    $appointment["AppointmentID"],
                                    6,
                                    "0",
                                    STR_PAD_LEFT
                                ); ?>
                                &nbsp;|&nbsp;
                                <?php echo date(
                                    "M j",
                                    strtotime($appointment["DateTime"])
                                ); ?> • <?php echo date(
                                    "g:i A",
                                    strtotime($appointment["DateTime"])
                                ); ?>
                            </div>
                            <span class="<?php echo getAppointmentStatusClass(
                                $appointment["Status"]
                            ); ?> px-2 py-1 rounded-full text-xs"><?php echo $appointment[
     "Status"
 ]; ?></span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">
                            Dr. <?php echo htmlspecialchars(
                                $appointment["DoctorFirstName"] .
                                    " " .
                                    $appointment["DoctorLastName"]
                            ); ?>
                        </h4>
                        <div class="text-sm text-gray-600 mb-2">
                            <?php echo htmlspecialchars(
                                $appointment["Specialization"]
                            ); ?>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-sm">
                                <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                    <?php echo htmlspecialchars(
                                        $appointment["AppointmentType"]
                                    ); ?>
                                </span>
                            </div>
                            <div class="flex space-x-1">
                                <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
class="bg-purple-500/80 text-white px-2 py-1 text-xs hover:bg-purple-600 transition-colors rounded-2xl">
                                    View Status
                                </button>
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Awaiting Approval</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-xs mt-2">
                            <div>
                                <span class="font-medium">Payment:</span> 
                                <?php if (
                                    isset(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]
                                    )
                                ): ?>
                                    <span class="font-mono">#<?php echo str_pad(
                                        $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["PaymentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?></span>
                                    <span class="<?php echo strtolower(
                                        $appointmentPayments[
                                            $appointment["AppointmentID"]
                                        ]["Status"]
                                    ) === "paid"
                                            ? "bg-green-100 text-green-800"
                                            : "bg-yellow-100 text-yellow-800 text-xs"; ?> px-2 py-1 rounded-full ml-1">
                                        <?php echo htmlspecialchars(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["Status"]
                                        ); ?>
                                    </span>
                                    <span class="ml-2 text-nhd-brown font-semibold">₱
                                        <?php echo number_format(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]["total_amount"] ?? 0,
                                            2
                                        ); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-xl">N/A</span>
                                    <span class="ml-2 text-gray-500 font-semibold">₱0.00</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto px-2">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-300 bg-gray-50/50">
                            <th class="text-left py-2 px-2 font-medium text-gray-700 text-xs w-24">Appointment ID</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Date & Time</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Doctor</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Type</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Status</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Payment</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/30">
                        <?php foreach (
                            $pendingCancellationAppointments as $appointment
                        ): ?>
                            <tr class="hover:bg-nhd-blue/10 cursor-pointer transition-colors duration-200 border-b-1 border-gray-200"
                                onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')"
                                tabindex="0" role="button"
                                onkeydown="if(event.key==='Enter'||event.key===' '){navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')}">
                                <td class="py-2 px-2 font-mono text-xs text-gray-700 w-24 font-bold">
                                    #<?php echo str_pad(
                                        $appointment["AppointmentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                                        <div class="font-medium">
                                            <?php echo date(
                                                "M j",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                        <div class="font-bold">
                                            <?php echo date(
                                                "g:i A",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="font-medium text-gray-900 text-sm">
                                        Dr. <?php echo htmlspecialchars(
                                            $appointment["DoctorFirstName"] .
                                                " " .
                                                $appointment["DoctorLastName"]
                                        ); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo htmlspecialchars(
                                            $appointment["Specialization"]
                                        ); ?>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-3">
                                <span class="<?php echo getAppointmentStatusClass(
                                    $appointment["Status"]
                                ); ?> px-2 py-1 rounded-full text-xs"><?php echo $appointment[
     "Status"
 ]; ?></span>
                                </td>
                                <td class="py-2 px-3">
                                    <?php if (
                                        isset(
                                            $appointmentPayments[
                                                $appointment["AppointmentID"]
                                            ]
                                        )
                                    ): ?>
                                        <div class="flex flex-row gap-1">
                                            <span class="<?php echo strtolower(
                                                $appointmentPayments[
                                                    $appointment[
                                                        "AppointmentID"
                                                    ]
                                                ]["Status"]
                                            ) === "paid"
                                                ? "bg-green-100 text-green-800"
                                                : "bg-yellow-100 text-yellow-800 text-xs"; ?> px-2 py-1 rounded-full">
                                                <?php echo htmlspecialchars(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]["Status"]
                                                ); ?>
                                            </span>
                                            <span class="text-nhd-brown font-semibold">₱
                                                <?php echo number_format(
                                                    $appointmentPayments[
                                                        $appointment[
                                                            "AppointmentID"
                                                        ]
                                                    ]["total_amount"] ?? 0,
                                                    2
                                                ); ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-xl">N/A</span>
                                        <span class="text-gray-500 font-semibold ml-2">₱0.00</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="flex items-center space-x-1">
                                        <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                                                class="bg-purple-500/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-purple-600 transition-colors">
                                            View Status
                                        </button>
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-xl text-xs">Awaiting Approval</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4 shadow-none border-1 border-gray-200">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-500">No pending cancellation requests</p>
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

