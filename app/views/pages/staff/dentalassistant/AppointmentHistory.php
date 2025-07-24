<?php
require_once __DIR__ . "/../../../components/SortableAppointmentTable.php";

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

function renderPendingCancellationTableForStaff(
    $pendingCancellations,
    $csrf_token
) {
    if (empty($pendingCancellations)) {
        return;
    } ?>

    <div class="bg-red-50/60 mb-8" id="pending-cancellation-requests-section">
        <div class="p-4 border-b border-red-200/50">
            <h3 class="text-2xl font-semibold text-red-700 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pending Cancellation Requests (<?php echo count(
                    $pendingCancellations
                ); ?>)
            </h3>
            <p class="text-red-600 text-sm mt-1">Appointment cancellation requests requiring doctor approval</p>
        </div>

        <div id="table-container-pending-cancellation-requests" class="table-container">
            <!-- Mobile View for Pending Cancellations -->
            <div class="block lg:hidden">
                <?php foreach (
                    $pendingCancellations as $index => $appointment
                ): ?>
                    <div class="p-4 border-b border-red-200/30 bg-white/40 table-row" data-row-index="<?php echo $index; ?>">
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
                        <div class="text-sm text-gray-600 mb-2">
                            <span class="font-medium">Doctor:</span> Dr. <?php echo htmlspecialchars(
                                $appointment["DoctorFirstName"] .
                                    " " .
                                    $appointment["DoctorLastName"]
                            ); ?>
                        </div>
                        <div class="text-sm text-gray-600 mb-3">
                            <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                <?php echo htmlspecialchars(
                                    $appointment["AppointmentType"]
                                ); ?>
                            </span>
                        </div>
                        <div class="text-sm text-gray-500 italic">
                            This request needs doctor approval
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Desktop Table View for Pending Cancellations -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full sortable-appointment-table" data-section="pending-cancellation-requests">
                    <thead>
                        <tr class="border-b border-red-200/60 bg-red-50/50">
                            <th class="sortable-header text-left py-2 px-3 font-medium text-red-700 text-sm cursor-pointer hover:bg-red-100/60 transition-colors" data-sort="DateTime">
                                Date & Time
                                <span class="sort-indicator ml-1">
                                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                        <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                        <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                    </svg>
                                    <span class="sort-icon-active hidden"></span>
                                </span>
                            </th>
                            <th class="sortable-header text-left py-2 px-3 font-medium text-red-700 text-sm cursor-pointer hover:bg-red-100/60 transition-colors" data-sort="PatientFirstName">
                                Patient
                                <span class="sort-indicator ml-1">
                                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                        <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                        <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                    </svg>
                                    <span class="sort-icon-active hidden"></span>
                                </span>
                            </th>
                            <th class="sortable-header text-left py-2 px-3 font-medium text-red-700 text-sm cursor-pointer hover:bg-red-100/60 transition-colors" data-sort="DoctorFirstName">
                                Doctor
                                <span class="sort-indicator ml-1">
                                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                        <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                        <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                    </svg>
                                    <span class="sort-icon-active hidden"></span>
                                </span>
                            </th>
                            <th class="sortable-header text-left py-2 px-3 font-medium text-red-700 text-sm cursor-pointer hover:bg-red-100/60 transition-colors" data-sort="PatientEmail">
                                Contact
                                <span class="sort-indicator ml-1">
                                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                        <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                        <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                    </svg>
                                    <span class="sort-icon-active hidden"></span>
                                </span>
                            </th>
                            <th class="sortable-header text-left py-2 px-3 font-medium text-red-700 text-sm cursor-pointer hover:bg-red-100/60 transition-colors" data-sort="AppointmentType">
                                Type
                                <span class="sort-indicator ml-1">
                                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                        <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                        <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                    </svg>
                                    <span class="sort-icon-active hidden"></span>
                                </span>
                            </th>
                            <th class="sortable-header text-left py-2 px-3 font-medium text-red-700 text-sm cursor-pointer hover:bg-red-100/60 transition-colors" data-sort="Reason">
                                Reason
                                <span class="sort-indicator ml-1">
                                    <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                        <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                        <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                    </svg>
                                    <span class="sort-icon-active hidden"></span>
                                </span>
                            </th>
                            <th class="text-left py-2 px-3 font-medium text-red-700 text-sm">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-200/30" id="table-body-pending-cancellation-requests">
                        <?php foreach (
                            $pendingCancellations as $index => $appointment
                        ): ?>
                            <tr class="hover:bg-white/60 transition-colors duration-200 table-row" data-row-index="<?php echo $index; ?>">
                                <td class="py-2 px-3">
                                    <div class="bg-red-100 text-red-700 px-2 py-1 rounded inline-block text-xs">
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
                                        <?php echo htmlspecialchars(
                                            $appointment["PatientFirstName"] .
                                                " " .
                                                $appointment["PatientLastName"]
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
                                    <div class="font-medium text-gray-900 text-sm">
                                        Dr. <?php echo htmlspecialchars(
                                            $appointment["DoctorFirstName"] .
                                                " " .
                                                $appointment["DoctorLastName"]
                                        ); ?>
                                    </div>
                                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars(
                                        $appointment["Specialization"] ?? ""
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
                                            $appointment["AppointmentType"]
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
                                    <span class="bg-purple-100/60 text-purple-800 px-2 py-1 rounded-full text-xs">
                                        Awaiting Doctor Approval
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls Bottom -->
            <?php echo renderPaginationControls(
                "pending-cancellation-requests",
                "bottom"
            ); ?>
        </div>

        <!-- Collapse Button -->
        <div class="text-center mt-4">
            <button
                onclick="toggleTableCollapse('pending-cancellation-requests')"
                id="collapse-btn-pending-cancellation-requests"
                class="text-black bg-transparent shadow-none transition-colors duration-200 text-sm font-medium cursor-pointer">
                <span class="collapse-text">Collapse Table</span>
                <svg class="inline-block w-4 h-4 ml-1 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        </div>
    </div>

    <?php
}

function renderStaffAppointmentTable(
    $appointments,
    $sectionId,
    $sectionTitle,
    $emptyMessage,
    $emptyIcon,
    $user
) {
    ?>

    <div id="<?php echo $sectionId; ?>-section" class="appointment-section">
        <div class="px-3 mb-3 mt-8">
            <h3 class="text-2xl font-semibold text-nhd-brown"><?php echo $sectionTitle; ?></h3>
        </div>

        <div id="table-container-<?php echo $sectionId; ?>" class="table-container">
            <?php if (!empty($appointments)): ?>
                <!-- Mobile View -->
                <div class="block lg:hidden" id="mobile-view-<?php echo $sectionId; ?>">
                    <?php foreach ($appointments as $index => $appointment): ?>
                        <div class="p-4 border-b border-gray-200/30 hover:bg-white/40 transition-colors rounded-2xl glass-card mb-3 table-row" data-row-index="<?php echo $index; ?>">
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
                                ); ?> px-2 py-1 rounded-full text-xs">
                                    <?php echo $appointment["Status"]; ?>
                                </span>
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

                            <div class="text-sm text-gray-600 mb-2">
                                <span class="font-medium">Doctor:</span> Dr. <?php echo htmlspecialchars(
                                    $appointment["DoctorFirstName"] .
                                        " " .
                                        $appointment["DoctorLastName"]
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
                                    <button onclick="showAppointmentDetails('<?php echo $appointment[
                                        "AppointmentID"
                                    ]; ?>')"
                                            class="bg-nhd-blue/80 text-white px-3 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                        View Details
                                    </button>
                                </div>
                            </div>

                            <?php if (!empty($appointment["Reason"])): ?>
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
                <div class="hidden lg:block overflow-x-auto px-2">
                    <table class="w-full sortable-appointment-table" data-section="<?php echo $sectionId; ?>">
                        <thead>
                            <tr class="border-b border-gray-300 bg-gray-50/50">
                                <th class="text-left py-2 px-2 font-medium text-gray-700 text-xs w-24">Appointment ID</th>
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
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorFirstName">
                                    Doctor
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
                                <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="Status">
                                    Status
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
                        <tbody class="divide-y divide-gray-200/30" id="table-body-<?php echo $sectionId; ?>">
                            <?php foreach (
                                $appointments as $index => $appointment
                            ): ?>
                                <tr class="hover:bg-white/60 transition-colors duration-200 table-row" data-row-index="<?php echo $index; ?>">
                                    <td class="py-2 px-2">
                                        <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                                            #<?php echo str_pad(
                                                $appointment["AppointmentID"],
                                                6,
                                                "0",
                                                STR_PAD_LEFT
                                            ); ?>
                                        </div>
                                    </td>
                                    <td class="py-2 px-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo date(
                                                "M j, Y",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo date(
                                                "g:i A",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?>
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
                                            <?php echo htmlspecialchars(
                                                $appointment["PatientEmail"]
                                            ); ?>
                                        </div>
                                    </td>
                                    <td class="py-2 px-3">
                                        <div class="font-medium text-gray-900 text-sm">
                                            Dr. <?php echo htmlspecialchars(
                                                $appointment[
                                                    "DoctorFirstName"
                                                ] .
                                                    " " .
                                                    $appointment[
                                                        "DoctorLastName"
                                                    ]
                                            ); ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?php echo htmlspecialchars(
                                                $appointment[
                                                    "Specialization"
                                                ] ?? ""
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
                                        ); ?> px-2 py-1 rounded-full text-xs">
                                            <?php echo $appointment[
                                                "Status"
                                            ]; ?>
                                        </span>
                                    </td>
                                    <td class="py-2 px-3">
                                        <button onclick="showAppointmentDetails('<?php echo $appointment[
                                            "AppointmentID"
                                        ]; ?>')"
                                                class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php echo renderPaginationControls($sectionId, "bottom"); ?>
            <?php else: ?>
                <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4 shadow-none border-1 border-gray-200">
                    <?php echo $emptyIcon; ?>
                    <p class="text-gray-500"><?php echo $emptyMessage; ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Collapse Button -->
        <div class="text-center mt-4">
            <button
                onclick="toggleTableCollapse('<?php echo $sectionId; ?>')"
                id="collapse-btn-<?php echo $sectionId; ?>"
                class="text-black bg-transparent shadow-none transition-colors duration-200 text-sm font-medium cursor-pointer">
                <span class="collapse-text">Collapse Table</span>
                <svg class="inline-block w-4 h-4 ml-1 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
    </div>

    <?php
}
?>

<div class="px-4 pb-8">
    <div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
                    All Appointments History
                </h1>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Total appointments: <?php
                        $totalAppointments = 0;
foreach (
    $appointmentHistory as $status => $appointments
) {
    $totalAppointments += count($appointments);
}
echo $totalAppointments;
?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex justify-between items-center my-4">
        <div class="flex space-x-2 flex-wrap">
            <button onclick="showSection('all')" id="all-btn" class="glass-card bg-nhd-blue/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-white">
                All Appointments
            </button>
            <?php if (!empty($pendingCancellations)): ?>
                <button onclick="showSection('pending-cancellation-requests')" id="pending-cancellation-requests-btn" class="glass-card bg-red-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-red-700">
                    Pending Cancellations (<?php echo count(
                        $pendingCancellations
                    ); ?>)
                </button>
            <?php endif; ?>
            <button onclick="showSection('pending')" id="pending-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Pending
            </button>
            <button onclick="showSection('approved')" id="approved-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Approved
            </button>
            <button onclick="showSection('rescheduled')" id="rescheduled-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Rescheduled
            </button>
            <button onclick="showSection('completed')" id="completed-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Completed
            </button>
            <button onclick="showSection('declined')" id="declined-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Declined
            </button>
            <button onclick="showSection('cancelled')" id="cancelled-btn" class="glass-card bg-gray-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-gray-700">
                Cancelled
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
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
                "Declined",
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
    <?php if (!empty($pendingCancellations)) {
        renderPendingCancellationTableForStaff(
            $pendingCancellations,
            $csrf_token ?? ""
        );
    } ?>

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
                <?php
            // Prepare section data
            $sectionId = strtolower(str_replace(" ", "-", $status));
                $sectionTitle =
                    $status .
                    ' Appointments <span class="text-sm font-normal text-gray-600">(' .
                    count($appointmentHistory[$status]) .
                    " appointments)</span>";
                $emptyMessage = "No {$status} appointments";
                $emptyIcon = '<svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>';

                // Render the section using the custom component for staff
                renderStaffAppointmentTable(
                    $appointmentHistory[$status],
                    $sectionId,
                    $sectionTitle,
                    $emptyMessage,
                    $emptyIcon,
                    $user
                );
                ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4 shadow-none border-1 border-gray-200">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-500">No appointment history available</p>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ .
    "/../../../components/SchedulePage/AppointmentDetailsModal.php"; ?>

<script>
function showAppointmentDetails(appointmentId) {
  if (typeof openAppointmentDetailsModal === 'function') {
    openAppointmentDetailsModal(appointmentId);
  } else {
    console.error('openAppointmentDetailsModal function not found');
  }
}

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

    /* Section visibility */
    .appointment-section {
        display: none;
    }

    .appointment-section.active {
        display: block;
    }
</style>

<script src="<?php echo BASE_URL; ?>/app/views/scripts/SortableTable.js"></script>
<script src="<?php echo BASE_URL; ?>/app/views/scripts/SchedulePage/AppointmentDetailsModal.js"></script>
<script src="<?php echo BASE_URL; ?>/app/views/scripts/SchedulePage/AppointmentDetailsModalAssistant.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Ensure table manager is initialized for appointment history tables
    setTimeout(() => {
        if (window.tableManager) {
            console.log("Reinitializing SortableTable for appointment history tables");
            window.tableManager.init();

            // Initialize pagination for all appointment table sections
            const sections = ['pending-cancellation-requests', 'pending', 'approved', 'completed', 'cancelled'];
            sections.forEach(section => {
                if (document.getElementById(`table-container-${section}`) && window.tableManager.paginationManager) {
                    window.tableManager.paginationManager.initializeSection(section);
                }
            });
        } else {
            console.warn("SortableTable manager not available for appointment history");
        }
    }, 200);
});
</script>
