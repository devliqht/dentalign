<?php
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
        default:
            return "bg-gray-100/60 text-gray-600";
    }
}

function getStatusHeaderClass($status)
{
    switch (strtolower($status)) {
        case "pending":
            return "text-yellow-700";
        case "approved":
            return "text-green-700";
        case "rescheduled":
            return "text-blue-700";
        case "completed":
            return "text-gray-700";
        case "declined":
            return "text-red-700";
        case "cancelled":
            return "text-orange-700";
        default:
            return "text-gray-700";
    }
}
?>

<div class="px-4 pb-8">
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
                                Appointment History
                            </h1>
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
                            <a href="<?php echo BASE_URL; ?>/doctor/schedule" class="glass-card bg-nhd-blue/80 text-white px-3 py-2 rounded-2xl text-sm font-medium hover:bg-nhd-blue transition-all duration-200">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Current Schedule
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <?php
                    $totalAppointments = 0;
foreach ($appointmentHistory as $status => $appointments) {
    $totalAppointments += count($appointments);
}
?>
                    
                    <div class="glass-card shadow-sm border-gray-200 border-1 bg-white/60 rounded-2xl p-4 text-center">
                        <div class="text-2xl font-bold text-nhd-brown"><?php echo $totalAppointments; ?></div>
                        <div class="text-sm text-gray-600">Total</div>
                    </div>
                    
                    <?php foreach (
                        [
                            "Pending",
                            "Approved",
                            "Rescheduled",
                            "Completed",
                            "Cancelled",
                        ] as $status
                    ): ?>
                        <div class="glass-card shadow-sm bg-white/60 border-gray-200 border-1 rounded-2xl p-4 text-center">
                            <div class="text-2xl font-bold <?php echo getStatusHeaderClass(
                                $status
                            ); ?>"><?php echo count(
                                $appointmentHistory[$status] ?? []
                            ); ?></div>
                            <div class="text-xs text-gray-600"><?php echo $status; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pending Cancellation Requests Section -->
                <?php if (!empty($pendingCancellations)): ?>
                <div class="bg-red-50/60 rounded-2xl border border-red-200/50 mb-8">
                    <div class="p-4 border-b border-red-200/50">
                        <h3 class="text-2xl font-semibold text-red-700 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pending Cancellation Requests (<?php echo count(
                                $pendingCancellations
                            ); ?>)
                        </h3>
                        <p class="text-red-600 text-sm mt-1">Appointment cancellation requests requiring your approval</p>
                    </div>
                    
                    <!-- Mobile View for Pending Cancellations -->
                    <div class="block lg:hidden">
                        <?php foreach (
                            $pendingCancellations as $appointment
                        ): ?>
                            <div class="p-4 border-b border-red-200/30 bg-white/40">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">
                                        <?php echo date(
                                            "M j",
                                            strtotime($appointment["DateTime"])
                                        ); ?> • <?php echo date(
                                            "g:i A",
                                            strtotime($appointment["DateTime"])
                                        ); ?>
                                    </div>
                                    <span class="bg-purple-100/60 text-purple-800 px-2 py-1 rounded-full text-xs">Pending Cancellation</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-1">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientFirstName"] .
                                            " " .
                                            $appointment["PatientLastName"]
                                    ); ?>
                                </h4>
                                <div class="text-sm text-gray-600 mb-2">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientEmail"]
                                    ); ?>
                                </div>
                                <div class="text-sm text-gray-600 mb-3">
                                    <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <form method="POST" action="<?php echo BASE_URL; ?>/doctor/approve-cancellation" class="inline">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment[
                                            "AppointmentID"
                                        ]; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                        <button type="submit" onclick="return confirm('Are you sure you want to approve this cancellation? This action cannot be undone.')" 
                                                class="bg-red-500/80 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition-colors">
                                            Approve Cancellation
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo BASE_URL; ?>/doctor/deny-cancellation" class="inline">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment[
                                            "AppointmentID"
                                        ]; ?>">
                                        <input type="hidden" name="new_status" value="Approved">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                        <button type="submit" onclick="return confirm('Are you sure you want to deny this cancellation? The appointment will remain scheduled.')" 
                                                class="bg-green-500/80 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition-colors">
                                            Deny Request
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Desktop Table View for Pending Cancellations -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-red-200/60 bg-red-50/50">
                                    <th class="text-left py-2 px-3 font-medium text-red-700 text-sm">Date & Time</th>
                                    <th class="text-left py-2 px-3 font-medium text-red-700 text-sm">Patient</th>
                                    <th class="text-left py-2 px-3 font-medium text-red-700 text-sm">Contact</th>
                                    <th class="text-left py-2 px-3 font-medium text-red-700 text-sm">Type</th>
                                    <th class="text-left py-2 px-3 font-medium text-red-700 text-sm">Reason</th>
                                    <th class="text-left py-2 px-3 font-medium text-red-700 text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-red-200/30">
                                <?php foreach (
                                    $pendingCancellations as $appointment
                                ): ?>
                                    <tr class="hover:bg-white/60 transition-colors duration-200">
                                        <td class="py-2 px-3">
                                            <div class="bg-red-100 text-red-700 px-2 py-1 rounded inline-block text-xs">
                                                <div class="font-medium">
                                                    <?php echo date(
                                                        "M j",
                                                        strtotime(
                                                            $appointment[
                                                                "DateTime"
                                                            ]
                                                        )
                                                    ); ?>
                                                </div>
                                                <div class="font-bold">
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
                                        </td>
                                        <td class="py-2 px-3">
                                            <div class="font-medium text-gray-900 text-sm">
                                                <?php echo htmlspecialchars(
                                                    $appointment[
                                                        "PatientFirstName"
                                                    ] .
                                                        " " .
                                                        $appointment[
                                                            "PatientLastName"
                                                        ]
                                                ); ?>
                                            </div>
                                            <div class="text-xs text-gray-500">ID #<?php echo str_pad(
                                                $appointment["AppointmentID"],
                                                4,
                                                "0",
                                                STR_PAD_LEFT
                                            ); ?></div>
                                        </td>
                                        <td class="py-2 px-3">
                                            <div class="text-sm text-gray-600">
                                                <?php echo htmlspecialchars(
                                                    $appointment["PatientEmail"]
                                                ); ?>
                                            </div>
                                        </td>
                                        <td class="py-2 px-3">
                                            <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                                <?php echo htmlspecialchars(
                                                    $appointment[
                                                        "AppointmentType"
                                                    ]
                                                ); ?>
                                            </span>
                                        </td>
                                        <td class="py-2 px-3 max-w-xs">
                                            <?php if (
                                                !empty($appointment["Reason"])
                                            ): ?>
                                                <div class="text-sm text-gray-600 truncate" title="<?php echo htmlspecialchars(
                                                    $appointment["Reason"]
                                                ); ?>">
                                                    <?php echo htmlspecialchars(
                                                        $appointment["Reason"]
                                                    ); ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-sm italic">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-2 px-3">
                                            <div class="flex space-x-2">
                                                <form method="POST" action="<?php echo BASE_URL; ?>/doctor/approve-cancellation" class="inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment[
                                                        "AppointmentID"
                                                    ]; ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                                    <button type="submit" onclick="return confirm('Are you sure you want to approve this cancellation? This action cannot be undone.')" 
                                                            class="bg-red-500/80 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition-colors">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="<?php echo BASE_URL; ?>/doctor/deny-cancellation" class="inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment[
                                                        "AppointmentID"
                                                    ]; ?>">
                                                    <input type="hidden" name="new_status" value="Approved">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                                    <button type="submit" onclick="return confirm('Are you sure you want to deny this cancellation? The appointment will remain scheduled.')" 
                                                            class="bg-green-500/80 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition-colors">
                                                        Deny
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Appointment History Sections by Status -->
                <?php
                $statusOrder = [
                    "Pending",
                    "Approved",
                    "Rescheduled",
                    "Completed",
                    "Declined",
                    "Cancelled",
                ];
$hasAnyAppointments = false;
foreach ($statusOrder as $status) {
    if (!empty($appointmentHistory[$status])) {
        $hasAnyAppointments = true;
        break;
    }
}
?>

                <?php if ($hasAnyAppointments): ?>
                    <?php foreach ($statusOrder as $status): ?>
                        <?php if (!empty($appointmentHistory[$status])): ?>
                            <div class="bg-gray-50/60 border-gray-200/50 rounded-2xl border mb-8">
                                <div class="p-4 border-b border-gray-200/50">
                                    <h3 class="text-xl font-semibold <?php echo getStatusHeaderClass(
                                        $status
                                    ); ?> flex items-center">
                                        <span class="<?php echo getAppointmentStatusClass(
                                            $status
                                        ); ?> px-3 py-1 rounded-full text-sm mr-3">
                                            <?php echo $status; ?>
                                        </span>
                                        (<?php echo count(
                                            $appointmentHistory[$status]
                                        ); ?> appointments)
                                    </h3>
                                </div>
                                
                                <!-- Mobile View -->
                                <div class="block lg:hidden">
                                    <?php foreach (
                                        $appointmentHistory[$status] as $appointment
                                    ): ?>
                                        <div class="p-4 border-b border-gray-200/30 hover:bg-white/40 transition-colors">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded text-xs font-medium">
                                                    <?php echo date(
                                                        "M j",
                                                        strtotime(
                                                            $appointment[
                                                                "DateTime"
                                                            ]
                                                        )
                                                    ); ?> • <?php echo date(
                                                        "g:i A",
                                                        strtotime($appointment["DateTime"])
                                                    ); ?>
                                                </div>
                                                <span class="<?php echo getAppointmentStatusClass(
                                                    $appointment["Status"]
                                                ); ?> px-2 py-1 rounded-full text-xs">
                                                    <?php echo $appointment[
                                                        "Status"
                                                    ]; ?>
                                                </span>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-1">
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
                                            <div class="text-sm text-gray-600 mb-2">
                                                <?php echo htmlspecialchars(
                                                    $appointment["PatientEmail"]
                                                ); ?>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <div class="text-sm">
                                                    <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                                        <?php echo htmlspecialchars(
                                                            $appointment[
                                                                "AppointmentType"
                                                            ]
                                                        ); ?>
                                                    </span>
                                                </div>
                                                <div class="flex space-x-1">
                                                    <button onclick="openAppointmentDetailsModal(<?php echo $appointment[
                                                        "AppointmentID"
                                                    ]; ?>)" 
                                                            class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                                        Details
                                                    </button>
                                                    <button class="bg-gray-200/80 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300/80 transition-colors">
                                                        Notes
                                                    </button>
                                                </div>
                                            </div>
                                            <?php if (
                                                !empty($appointment["Reason"])
                                            ): ?>
                                                <div class="mt-2 text-sm text-gray-600">
                                                    <span class="font-medium">Reason:</span> <?php echo htmlspecialchars(
                                                        $appointment["Reason"]
                                                    ); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Desktop Table View -->
                                <div class="hidden lg:block overflow-x-auto">
                                    <table class="w-full" data-status="<?php echo $status; ?>">
                                        <thead>
                                            <tr class="border-b border-gray-200/60 bg-gray-50/50">
                                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DateTime">
                                                    Date & Time 
                                                    <span class="sort-indicator ml-1">
                                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                                        </svg>
                                                        <span class="sort-icon-active hidden"></span>
                                                    </span>
                                                </th>
                                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientFirstName">
                                                    Patient 
                                                    <span class="sort-indicator ml-1">
                                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                                        </svg>
                                                        <span class="sort-icon-active hidden"></span>
                                                    </span>
                                                </th>
                                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientEmail">
                                                    Contact 
                                                    <span class="sort-indicator ml-1">
                                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                                        </svg>
                                                        <span class="sort-icon-active hidden"></span>
                                                    </span>
                                                </th>
                                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentType">
                                                    Type 
                                                    <span class="sort-indicator ml-1">
                                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                                        </svg>
                                                        <span class="sort-icon-active hidden"></span>
                                                    </span>
                                                </th>
                                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="Reason">
                                                    Reason 
                                                    <span class="sort-indicator ml-1">
                                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                                        </svg>
                                                        <span class="sort-icon-active hidden"></span>
                                                    </span>
                                                </th>
                                                <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200/30" id="table-body-<?php echo strtolower(
                                            str_replace(" ", "-", $status)
                                        ); ?>">
                                            <?php foreach (
                                                $appointmentHistory[$status] as $appointment
                                            ): ?>
                                                <tr class="hover:bg-white/40 transition-colors duration-200">
                                                    <td class="py-2 px-3">
                                                        <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                                                            <div class="font-medium">
                                                                <?php echo date(
                                                                    "M j",
                                                                    strtotime(
                                                                        $appointment[
                                                                            "DateTime"
                                                                        ]
                                                                    )
                                                                ); ?>
                                                            </div>
                                                            <div class="font-bold">
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
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <div class="font-medium text-gray-900 text-sm">
                                                            <?php echo htmlspecialchars(
                                                                $appointment[
                                                                    "PatientFirstName"
                                                                ] .
                                                                    " " .
                                                                    $appointment[
                                                                        "PatientLastName"
                                                                    ]
                                                            ); ?>
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
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <div class="text-sm text-gray-600">
                                                            <?php echo htmlspecialchars(
                                                                $appointment[
                                                                    "PatientEmail"
                                                                ]
                                                            ); ?>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                                            <?php echo htmlspecialchars(
                                                                $appointment[
                                                                    "AppointmentType"
                                                                ]
                                                            ); ?>
                                                        </span>
                                                    </td>
                                                    <td class="py-2 px-3 max-w-xs">
                                                        <?php if (
                                                            !empty(
                                                                $appointment[
                                                                    "Reason"
                                                                ]
                                                            )
                                                        ): ?>
                                                            <div class="text-sm text-gray-600 truncate" title="<?php echo htmlspecialchars(
                                                                $appointment[
                                                                        "Reason"
                                                                    ]
                                                            ); ?>">
                                                                <?php echo htmlspecialchars(
                                                                    $appointment[
                                                                        "Reason"
                                                                    ]
                                                                ); ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-gray-400 text-sm italic">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="py-2 px-3">
                                                        <div class="flex space-x-1">
                                                            <button onclick="openAppointmentDetailsModal(<?php echo $appointment[
                                                                "AppointmentID"
                                                            ]; ?>)" 
                                                                    class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                                                Details
                                                            </button>
                                                            <button class="bg-gray-200/80 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300/80 transition-colors">
                                                                Notes
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Empty state -->
                    <div class="bg-white/60 rounded-2xl border border-gray-200/50 text-center py-12">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No appointment history yet</h3>
                        <p class="text-gray-500 mb-4">Your appointments will appear here organized by status once you start seeing patients.</p>
                        <a href="<?php echo BASE_URL; ?>/doctor/schedule" class="glass-card bg-nhd-blue/80 text-white px-6 py-2 rounded-lg font-medium hover:bg-nhd-blue transition-colors">
                            View Current Schedule
                        </a>
                    </div>
                <?php endif; ?>
</div>

<!-- Appointment Details Modal -->
<?php include "app/views/components/SchedulePage/AppointmentDetailsModal.php"; ?>

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
    <?php if (isset($_SESSION["info"])): ?>
        info: <?php echo json_encode($_SESSION["info"]); ?>,
        <?php unset($_SESSION["info"]); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION["warning"])): ?>
        warning: <?php echo json_encode($_SESSION["warning"]); ?>,
        <?php unset($_SESSION["warning"]); ?>
    <?php endif; ?>
};
</script>

<style>
.sortable-header {
    user-select: none;
    position: relative;
}

.sortable-header:hover {
    background-color: rgba(229, 231, 235, 0.6) !important;
}

.sort-indicator {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 16px;
    height: 16px;
    transition: all 0.2s ease;
}

.sort-icon-default {
    opacity: 0.5;
    transition: opacity 0.2s ease;
}

.sortable-header:hover .sort-icon-default {
    opacity: 0.8;
}

.sort-icon-active {
    font-size: 12px;
    font-weight: bold;
    color: #374151;
}

.sort-icon-active.asc::before {
    content: '▲';
    color: #059669;
}

.sort-icon-active.desc::before {
    content: '▼';
    color: #DC2626;
}

/* Active sorting state */
.sortable-header.sorting .sort-icon-default {
    display: none;
}

.sortable-header.sorting .sort-icon-active {
    display: inline-block;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortStates = {};
    
    const sortableHeaders = document.querySelectorAll('.sortable-header');
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const table = this.closest('table');
            const status = table.getAttribute('data-status');
            const sortKey = this.getAttribute('data-sort');
            
            // Initialize sort state for this status if it doesn't exist
            if (!sortStates[status]) {
                sortStates[status] = { key: null, direction: 'asc' };
            }
            
            if (sortStates[status].key === sortKey) {
                sortStates[status].direction = sortStates[status].direction === 'asc' ? 'desc' : 'asc';
            } else {
                sortStates[status].key = sortKey;
                sortStates[status].direction = 'asc';
            }
            
            // Perform the sort
            sortTableByStatus(status, sortKey, sortStates[status].direction);
            
            updateSortIndicators(table, sortKey, sortStates[status].direction);
        });
    });
    
    async function sortTableByStatus(status, sortKey, direction) {
        const tableBody = document.getElementById(`table-body-${status.toLowerCase().replace(/\s+/g, '-')}`);
        
        if (!tableBody) return;
        
        const originalOpacity = tableBody.style.opacity;
        tableBody.style.opacity = '0.5';
        
        try {
            const url = `${window.BASE_URL}/doctor/sortAppointmentHistory/${sortKey}/${direction}/${encodeURIComponent(status)}`;
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            
            const sortedAppointments = await response.json();
            renderTableBody(tableBody, sortedAppointments);
            
        } catch (error) {
            console.error('Failed to sort appointments:', error);
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-600">Error loading sorted data. Please try again.</td></tr>';
        } finally {
            tableBody.style.opacity = originalOpacity || '1';
        }
    }
    
    function renderTableBody(tableBody, appointments) {
        if (appointments.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-gray-500">No appointments found.</td></tr>';
            return;
        }
        
        const rowsHtml = appointments.map(appointment => {
            const appointmentDate = new Date(appointment.DateTime);
            const formattedDate = appointmentDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const formattedTime = appointmentDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
            
            const reasonHtml = appointment.Reason && appointment.Reason.trim()
                ? `<div class="text-sm text-gray-600 truncate" title="${escapeHtml(appointment.Reason)}">${escapeHtml(appointment.Reason)}</div>`
                : '<span class="text-gray-400 text-sm italic">-</span>';
            
            return `
                <tr class="hover:bg-white/40 transition-colors duration-200">
                    <td class="py-2 px-3">
                        <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                            <div class="font-medium">${formattedDate}</div>
                            <div class="font-bold">${formattedTime}</div>
                        </div>
                    </td>
                    <td class="py-2 px-3">
                        <div class="font-medium text-gray-900 text-sm">${escapeHtml(appointment.PatientFirstName + ' ' + appointment.PatientLastName)}</div>
                        <div class="text-xs text-gray-500">ID #${String(appointment.AppointmentID).padStart(4, '0')}</div>
                    </td>
                    <td class="py-2 px-3">
                        <div class="text-sm text-gray-600">${escapeHtml(appointment.PatientEmail)}</div>
                    </td>
                    <td class="py-2 px-3">
                        <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">${escapeHtml(appointment.AppointmentType)}</span>
                    </td>
                    <td class="py-2 px-3 max-w-xs">${reasonHtml}</td>
                    <td class="py-2 px-3">
                        <div class="flex space-x-1">
                            <button onclick="openAppointmentDetailsModal(${appointment.AppointmentID})" 
                                    class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                Details
                            </button>
                            <button class="bg-gray-200/80 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300/80 transition-colors">
                                Notes
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
        
        tableBody.innerHTML = rowsHtml;
    }
    
    function updateSortIndicators(table, currentSortKey, direction) {
        // Reset all headers in this table
        const allHeaders = table.querySelectorAll('.sortable-header');
        allHeaders.forEach(header => {
            header.classList.remove('sorting');
            const defaultIcon = header.querySelector('.sort-icon-default');
            const activeIcon = header.querySelector('.sort-icon-active');
            
            if (defaultIcon) defaultIcon.style.display = 'inline-block';
            if (activeIcon) {
                activeIcon.style.display = 'none';
                activeIcon.classList.remove('asc', 'desc');
            }
        });
        
        // Set active state for current sort
        const currentHeader = table.querySelector(`[data-sort="${currentSortKey}"]`);
        if (currentHeader) {
            currentHeader.classList.add('sorting');
            const defaultIcon = currentHeader.querySelector('.sort-icon-default');
            const activeIcon = currentHeader.querySelector('.sort-icon-active');
            
            if (defaultIcon) defaultIcon.style.display = 'none';
            if (activeIcon) {
                activeIcon.style.display = 'inline-block';
                activeIcon.classList.add(direction);
            }
        }
    }
    
    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
</script>


