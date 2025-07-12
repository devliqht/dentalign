<?php
function getPaymentStatusClass($status)
{
    switch (strtolower($status)) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'paid':
            return 'bg-green-100 text-green-800';
        case 'overdue':
            return 'bg-red-100 text-red-800';
        case 'failed':
            return 'bg-red-100 text-red-800';
        case 'refunded':
            return 'bg-blue-100 text-blue-800';
        case 'cancelled':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function renderSortableAppointmentTable($appointments, $appointmentPayments, $sectionId, $sectionTitle, $emptyMessage, $emptyIcon, $user, $isPatientView = true)
{
    $hasPayments = !empty($appointmentPayments) && $isPatientView;
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
                                    <?php if ($isPatientView): ?>
                                        #<?php echo str_pad($appointment["AppointmentID"], 6, "0", STR_PAD_LEFT); ?>
                                        &nbsp;|&nbsp;
                                    <?php endif; ?>
                                    <?php echo date("M j", strtotime($appointment["DateTime"])); ?> • <?php echo date("g:i A", strtotime($appointment["DateTime"])); ?>
                                </div>
                                <span class="<?php echo getAppointmentStatusClass($appointment["Status"]); ?> px-2 py-1 rounded-full text-xs">
                                    <?php echo $appointment["Status"]; ?>
                                </span>
                            </div>
                            
                            <?php if ($isPatientView): ?>
                                <h4 class="font-semibold text-gray-900 mb-1">
                                    Dr. <?php echo htmlspecialchars($appointment["DoctorFirstName"] . " " . $appointment["DoctorLastName"]); ?>
                                </h4>
                                
                                <div class="text-sm text-gray-600 mb-2">
                                    <?php echo htmlspecialchars($appointment["Specialization"]); ?>
                                </div>
                            <?php else: ?>
                                <h4 class="font-semibold text-gray-900 mb-1">
                                    <?php echo htmlspecialchars($appointment["PatientFirstName"] . " " . $appointment["PatientLastName"]); ?>
                                </h4>
                                
                                <div class="text-sm text-gray-600 mb-2">
                                    <?php echo htmlspecialchars($appointment["PatientEmail"]); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex justify-between items-center mb-2">
                                <div class="text-sm">
                                    <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                        <?php echo htmlspecialchars($appointment["AppointmentType"]); ?>
                                    </span>
                                </div>
                                <div class="flex space-x-1">
                                    <?php echo renderMobileActions($appointment, $user, $appointmentPayments, $sectionId, $isPatientView); ?>
                                </div>
                            </div>
                            
                            <?php if ($hasPayments): ?>
                                <div class="flex justify-between items-center text-xs mt-2">
                                    <div>
                                        <span class="font-medium">Payment:</span> 
                                        <?php echo renderPaymentInfo($appointment, $appointmentPayments, true); ?>
                                    </div>
                                </div>
                            <?php elseif (!$isPatientView && !empty($appointment["Reason"])): ?>
                                <div class="mt-2 text-sm text-gray-600">
                                    <span class="font-medium">Reason:</span> <?php echo htmlspecialchars($appointment["Reason"]); ?>
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
                                <?php if ($isPatientView): ?>
                                    <th class="text-left py-2 px-2 font-medium text-gray-700 text-xs w-24">Appointment ID</th>
                                <?php endif; ?>
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
                                <?php if ($isPatientView): ?>
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
                                <?php else: ?>
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
                                <?php endif; ?>
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
                                <?php if ($isPatientView): ?>
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
                                <?php else: ?>
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
                                <?php endif; ?>
                                <?php if ($hasPayments): ?>
                                    <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Payment</th>
                                <?php endif; ?>
                                <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/30" id="table-body-<?php echo $sectionId; ?>">
                            <?php foreach ($appointments as $index => $appointment): ?>
                                <tr class="hover:bg-nhd-blue/10 cursor-pointer transition-colors duration-200 border-b-1 border-gray-200 table-row" data-row-index="<?php echo $index; ?>"
                                    <?php if ($isPatientView): ?>
                                    onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')"
                                    tabindex="0" role="button"
                                    onkeydown="if(event.key==='Enter'||event.key===' '){navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')}"
                                    <?php endif; ?>>
                                    <?php if ($isPatientView): ?>
                                        <td class="py-2 px-2 font-mono text-xs text-gray-700 w-24 font-bold">
                                            #<?php echo str_pad($appointment["AppointmentID"], 6, "0", STR_PAD_LEFT); ?>
                                        </td>
                                    <?php endif; ?>
                                    <td class="py-2 px-3">
                                        <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                                            <div class="font-medium">
                                                <?php echo date("M j", strtotime($appointment["DateTime"])); ?>
                                            </div>
                                            <div class="font-bold">
                                                <?php echo date("g:i A", strtotime($appointment["DateTime"])); ?>
                                            </div>
                                        </div>
                                    </td>
                                    <?php if ($isPatientView): ?>
                                        <td class="py-2 px-3">
                                            <div class="font-medium text-gray-900 text-sm">
                                                Dr. <?php echo htmlspecialchars($appointment["DoctorFirstName"] . " " . $appointment["DoctorLastName"]); ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                <?php echo htmlspecialchars($appointment["Specialization"]); ?>
                                            </div>
                                        </td>
                                    <?php else: ?>
                                        <td class="py-2 px-3">
                                            <div class="font-medium text-gray-900 text-sm">
                                                <?php echo htmlspecialchars($appointment["PatientFirstName"] . " " . $appointment["PatientLastName"]); ?>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ID #<?php echo str_pad($appointment["AppointmentID"], 4, "0", STR_PAD_LEFT); ?>
                                            </div>
                                        </td>
                                        <td class="py-2 px-3">
                                            <div class="text-sm text-gray-600">
                                                <?php echo htmlspecialchars($appointment["PatientEmail"]); ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                    <td class="py-2 px-3">
                                        <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                            <?php echo htmlspecialchars($appointment["AppointmentType"]); ?>
                                        </span>
                                    </td>
                                    <?php if ($isPatientView): ?>
                                        <td class="py-2 px-3">
                                            <span class="<?php echo getAppointmentStatusClass($appointment["Status"]); ?> px-2 py-1 rounded-full text-xs">
                                                <?php echo $appointment["Status"]; ?>
                                            </span>
                                        </td>
                                    <?php else: ?>
                                        <td class="py-2 px-3 max-w-xs">
                                            <?php if (!empty($appointment["Reason"])): ?>
                                                <div class="text-sm text-gray-600 truncate" title="<?php echo htmlspecialchars($appointment["Reason"]); ?>">
                                                    <?php echo htmlspecialchars($appointment["Reason"]); ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-sm italic">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <?php if ($hasPayments): ?>
                                        <td class="py-2 px-3">
                                            <?php echo renderPaymentInfo($appointment, $appointmentPayments, false); ?>
                                        </td>
                                    <?php endif; ?>
                                    <td class="py-2 px-3">
                                        <?php echo renderDesktopActions($appointment, $user, $appointmentPayments, $sectionId, $isPatientView); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls Bottom -->
                <?php echo renderPaginationControls($sectionId, 'bottom'); ?>
            <?php else: ?>
                <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center m-4 shadow-none border-1 border-gray-200">
                    <?php echo $emptyIcon; ?>
                    <p class="text-gray-500 mb-4"><?php echo $emptyMessage; ?></p>
                    <?php if ($sectionId === 'upcoming' && $isPatientView): ?>
                        <a href="<?php echo BASE_URL; ?>/patient/book-appointment" 
                           class="inline-flex items-center px-4 py-2 glass-card bg-nhd-blue/80 text-white rounded-2xl hover:bg-nhd-blue transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Book Your First Appointment
                        </a>
                    <?php endif; ?>
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

function renderTableRow($appointment, $user, $appointmentPayments, $sectionId, $hasPayments, $isPatientView = true)
{
    ob_start();
    ?>
    <tr class="hover:bg-nhd-blue/10 cursor-pointer transition-colors duration-200 border-b-1 border-gray-200"
        <?php if ($isPatientView): ?>
        onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')"
        tabindex="0" role="button"
        onkeydown="if(event.key==='Enter'||event.key===' '){navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')}"
        <?php endif; ?>>
        <?php if ($isPatientView): ?>
            <td class="py-2 px-2 font-mono text-xs text-gray-700 w-24 font-bold">
                #<?php echo str_pad($appointment["AppointmentID"], 6, "0", STR_PAD_LEFT); ?>
            </td>
        <?php endif; ?>
        <td class="py-2 px-3">
            <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                <div class="font-medium">
                    <?php echo date("M j", strtotime($appointment["DateTime"])); ?>
                </div>
                <div class="font-bold">
                    <?php echo date("g:i A", strtotime($appointment["DateTime"])); ?>
                </div>
            </div>
        </td>
        <?php if ($isPatientView): ?>
            <td class="py-2 px-3">
                <div class="font-medium text-gray-900 text-sm">
                    Dr. <?php echo htmlspecialchars($appointment["DoctorFirstName"] . " " . $appointment["DoctorLastName"]); ?>
                </div>
                <div class="text-xs text-gray-500">
                    <?php echo htmlspecialchars($appointment["Specialization"]); ?>
                </div>
            </td>
        <?php else: ?>
            <td class="py-2 px-3">
                <div class="font-medium text-gray-900 text-sm">
                    <?php echo htmlspecialchars($appointment["PatientFirstName"] . " " . $appointment["PatientLastName"]); ?>
                </div>
                <div class="text-xs text-gray-500">
                    ID #<?php echo str_pad($appointment["AppointmentID"], 4, "0", STR_PAD_LEFT); ?>
                </div>
            </td>
            <td class="py-2 px-3">
                <div class="text-sm text-gray-600">
                    <?php echo htmlspecialchars($appointment["PatientEmail"]); ?>
                </div>
            </td>
        <?php endif; ?>
        <td class="py-2 px-3">
            <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                <?php echo htmlspecialchars($appointment["AppointmentType"]); ?>
            </span>
        </td>
        <?php if ($isPatientView): ?>
            <td class="py-2 px-3">
                <span class="<?php echo getAppointmentStatusClass($appointment["Status"]); ?> px-2 py-1 rounded-full text-xs">
                    <?php echo $appointment["Status"]; ?>
                </span>
            </td>
        <?php else: ?>
            <td class="py-2 px-3 max-w-xs">
                <?php if (!empty($appointment["Reason"])): ?>
                    <div class="text-sm text-gray-600 truncate" title="<?php echo htmlspecialchars($appointment["Reason"]); ?>">
                        <?php echo htmlspecialchars($appointment["Reason"]); ?>
                    </div>
                <?php else: ?>
                    <span class="text-gray-400 text-sm italic">-</span>
                <?php endif; ?>
            </td>
        <?php endif; ?>
        <?php if ($hasPayments): ?>
            <td class="py-2 px-3">
                <?php echo renderPaymentInfo($appointment, $appointmentPayments, false); ?>
            </td>
        <?php endif; ?>
        <td class="py-2 px-3">
            <?php echo renderDesktopActions($appointment, $user, $appointmentPayments, $sectionId, $isPatientView); ?>
        </td>
    </tr>
    <?php
    return ob_get_clean();
}

function renderPaymentInfo($appointment, $appointmentPayments, $isMobile = false)
{
    ob_start();
    if (isset($appointmentPayments[$appointment["AppointmentID"]])) {
        $payment = $appointmentPayments[$appointment["AppointmentID"]];
        $statusClass = getPaymentStatusClass($payment["Status"]);

        if ($isMobile) {
            ?>
            <span class="font-mono">#<?php echo str_pad($payment["PaymentID"], 6, "0", STR_PAD_LEFT); ?></span>
            <span class="<?php echo $statusClass; ?> px-2 py-1 rounded-full ml-1 text-xs">
                <?php echo htmlspecialchars($payment["Status"]); ?>
            </span>
            <span class="ml-2 text-nhd-brown font-semibold">₱<?php echo number_format($payment["total_amount"] ?? 0, 2); ?></span>
            <?php
        } else {
            ?>
            <div class="flex flex-row gap-1">
                <span class="<?php echo $statusClass; ?> px-2 py-1 rounded-full text-xs">
                    <?php echo htmlspecialchars($payment["Status"]); ?>
                </span>
                <span class="text-nhd-brown font-semibold">₱<?php echo number_format($payment["total_amount"] ?? 0, 2); ?></span>
            </div>
            <?php
        }
    } else {
        $statusClass = getPaymentStatusClass('pending');
        if ($isMobile) {
            echo '<span class="' . $statusClass . ' text-xs px-2 py-1 rounded-xl">Pending</span>';
            echo '<span class="ml-2 text-nhd-brown font-semibold">₱0.00</span>';
        } else {
            echo '<span class="' . $statusClass . ' text-xs px-2 py-1 rounded-xl">Pending</span>';
            echo '<span class="text-nhd-brown font-semibold ml-2">₱0.00</span>';
        }
    }
    return ob_get_clean();
}

function renderMobileActions($appointment, $user, $appointmentPayments, $sectionId, $isPatientView = true)
{
    ob_start();

    if ($isPatientView) {
        if ($sectionId === 'upcoming') {
            // New cancellation logic
            $appointmentDateTime = strtotime($appointment["DateTime"]);
            $currentTime = time();
            $timeDifference = $appointmentDateTime - $currentTime;
            $isWithin24Hours = $timeDifference < 86400; // 24 hours = 86400 seconds
            
            // Check if payment is paid
            $payment = isset($appointmentPayments[$appointment["AppointmentID"]]) ? $appointmentPayments[$appointment["AppointmentID"]] : null;
            $isPaid = $payment && strtolower($payment["Status"]) === "paid";
            
            // Determine if cancellation should be disabled
            $canCancel = !$isPaid && !$isWithin24Hours;
            
            if (!$canCancel) {
                ?>
                <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                        class="bg-gray-500/80 text-white px-2 py-1 rounded text-xs hover:bg-gray-600 transition-colors">
                    View Details
                </button>
                <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">
                    <?php if ($isPaid): ?>
                        Paid - No Cancel
                    <?php elseif ($isWithin24Hours): ?>
                        < 24hrs - No Cancel
                    <?php else: ?>
                        No Cancellation
                    <?php endif; ?>
                </span>
                <?php
            } else {
                ?>
                <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                        class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                    Manage
                </button>
                <?php
            }
        } elseif ($sectionId === 'completed') {
            ?>
            <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                    class="bg-nhd-blue/80 text-white px-2 py-1 text-xs hover:bg-nhd-blue transition-colors rounded-2xl">
                View Report
            </button>
            <?php
        } elseif ($sectionId === 'cancelled') {
            ?>
            <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                    class="bg-gray-500/80 text-white px-2 py-1 text-xs hover:bg-gray-600 transition-colors rounded-2xl">
                View Details
            </button>
            <?php
        } elseif ($sectionId === 'pending-cancellation') {
            ?>
            <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                    class="bg-purple-500/80 text-white px-2 py-1 text-xs hover:bg-purple-600 transition-colors rounded-2xl">
                View Status
            </button>
            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">Awaiting Approval</span>
            <?php
        }
    } else {
        ?>
        <button onclick="openAppointmentDetailsModal(<?php echo $appointment["AppointmentID"]; ?>)" 
                class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
            Details
        </button>
        <?php
    }

    return ob_get_clean();
}

function renderDesktopActions($appointment, $user, $appointmentPayments, $sectionId, $isPatientView = true)
{
    ob_start();

    if ($isPatientView) {
        if ($sectionId === 'upcoming') {
            // New cancellation logic
            $appointmentDateTime = strtotime($appointment["DateTime"]);
            $currentTime = time();
            $timeDifference = $appointmentDateTime - $currentTime;
            $isWithin24Hours = $timeDifference < 86400; // 24 hours = 86400 seconds
            
            // Check if payment is paid
            $payment = isset($appointmentPayments[$appointment["AppointmentID"]]) ? $appointmentPayments[$appointment["AppointmentID"]] : null;
            $isPaid = $payment && strtolower($payment["Status"]) === "paid";
            
            // Determine if cancellation should be disabled
            $canCancel = !$isPaid && !$isWithin24Hours;
            
            if (!$canCancel) {
                ?>
                <div class="flex items-center space-x-1">
                    <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                            class="bg-gray-500/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-gray-600 transition-colors">
                        View Details
                    </button>
                    <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-xl text-xs">
                        <?php if ($isPaid): ?>
                            Paid - No Cancel
                        <?php elseif ($isWithin24Hours): ?>
                            < 24hrs - No Cancel
                        <?php else: ?>
                            No Cancellation
                        <?php endif; ?>
                    </span>
                </div>
                <?php
            } else {
                ?>
                <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                        class="bg-nhd-blue/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-nhd-blue transition-colors">
                    Manage
                </button>
                <?php
            }
        } elseif ($sectionId === 'completed') {
            ?>
            <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                    class="bg-nhd-blue/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-nhd-blue transition-colors">
                View Report
            </button>
            <?php
        } elseif ($sectionId === 'cancelled') {
            ?>
            <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                    class="bg-gray-500/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-gray-600 transition-colors">
                View Details
            </button>
            <?php
        } elseif ($sectionId === 'pending-cancellation') {
            ?>
            <div class="flex items-center space-x-1">
                <button onclick="navigateToAppointment('<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user["id"]; ?>/<?php echo $appointment["AppointmentID"]; ?>')" 
                        class="bg-purple-500/80 text-white px-2 py-1 rounded-xl text-xs hover:bg-purple-600 transition-colors">
                    View Status
                </button>
                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-xl text-xs">Awaiting Approval</span>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="flex space-x-1">
            <button onclick="openAppointmentDetailsModal(<?php echo $appointment["AppointmentID"]; ?>)" 
                    class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                Details
            </button>
        </div>
        <?php
    }

    return ob_get_clean();
}

function renderPaginationControls($sectionId, $position = 'bottom')
{
    ob_start();
    ?>
    <div class="pagination-controls pagination-<?php echo $position; ?> flex justify-between items-center px-4 py-3 bg-gray-50/30 <?php echo $position === 'top' ? 'border-b' : 'border-t'; ?> border-gray-200/50" data-section="<?php echo $sectionId; ?>">
        <!-- Left side: Showing X-Y of Z entries -->
        <div class="pagination-info text-sm text-gray-600">
            <span id="pagination-info-<?php echo $sectionId; ?>">Showing 1-10 of 0 entries</span>
        </div>
        
        <!-- Center: Rows per page -->
        <div class="pagination-center flex items-center space-x-2">
            <label for="rowsPerPage-<?php echo $sectionId; ?>" class="text-sm text-gray-600">Rows:</label>
            <select id="rowsPerPage-<?php echo $sectionId; ?>" 
                    class="text-sm border border-gray-300 rounded px-2 py-1 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-nhd-blue/50 focus:border-nhd-blue/50"
                    onchange="handleRowsPerPageChange('<?php echo $sectionId; ?>', this.value)">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        
        <!-- Right side: Navigation buttons -->
        <div class="pagination-nav flex items-center space-x-1">
            <button id="prevBtn-<?php echo $sectionId; ?>" 
                    onclick="navigatePage('<?php echo $sectionId; ?>', 'prev')"
                    class="px-3 py-1 text-sm text-black bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Previous
            </button>
            
            <span class="pagination-current text-sm text-gray-600 mx-2">
                Page <span id="currentPage-<?php echo $sectionId; ?>">1</span> of <span id="totalPages-<?php echo $sectionId; ?>">1</span>
            </span>
            
            <button id="nextBtn-<?php echo $sectionId; ?>" 
                    onclick="navigatePage('<?php echo $sectionId; ?>', 'next')"
                    class="px-3 py-1 text-sm text-black bg-white border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                Next
                <svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function renderPendingCancellationTable($pendingCancellations, $csrf_token)
{
    if (empty($pendingCancellations)) {
        return;
    }
    ?>
    
    <div class="bg-red-50/60 mb-8">
        <div class="p-4 border-b border-red-200/50">
            <h3 class="text-2xl font-semibold text-red-700 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pending Cancellation Requests (<?php echo count($pendingCancellations); ?>)
            </h3>
            <p class="text-red-600 text-sm mt-1">Appointment cancellation requests requiring your approval</p>
        </div>
        
        <div id="table-container-pending-cancellation-requests" class="table-container">
            <!-- Mobile View for Pending Cancellations -->
            <div class="block lg:hidden">
                <?php foreach ($pendingCancellations as $index => $appointment): ?>
                    <div class="p-4 border-b border-red-200/30 bg-white/40 table-row" data-row-index="<?php echo $index; ?>">
                        <div class="flex justify-between items-start mb-3">
                            <div class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-medium">
                                <?php echo date("M j", strtotime($appointment["DateTime"])); ?> • <?php echo date("g:i A", strtotime($appointment["DateTime"])); ?>
                            </div>
                            <span class="bg-purple-100/60 text-purple-800 px-2 py-1 rounded-full text-xs">Pending Cancellation</span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">
                            <?php echo htmlspecialchars($appointment["PatientFirstName"] . " " . $appointment["PatientLastName"]); ?>
                        </h4>
                        <div class="text-sm text-gray-600 mb-2">
                            <?php echo htmlspecialchars($appointment["PatientEmail"]); ?>
                        </div>
                        <div class="text-sm text-gray-600 mb-3">
                            <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                <?php echo htmlspecialchars($appointment["AppointmentType"]); ?>
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <form method="POST" action="<?php echo BASE_URL; ?>/doctor/approve-cancellation" class="inline">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment["AppointmentID"]; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to approve this cancellation? This action cannot be undone.')" 
                                        class="bg-red-500/80 text-white px-3 py-1 rounded text-xs hover:bg-red-600 transition-colors">
                                    Approve Cancellation
                                </button>
                            </form>
                            <form method="POST" action="<?php echo BASE_URL; ?>/doctor/deny-cancellation" class="inline">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment["AppointmentID"]; ?>">
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
                            <th class="text-left py-2 px-3 font-medium text-red-700 text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-200/30" id="table-body-pending-cancellation-requests">
                        <?php foreach ($pendingCancellations as $index => $appointment): ?>
                            <tr class="hover:bg-white/60 transition-colors duration-200 table-row" data-row-index="<?php echo $index; ?>">
                                <td class="py-2 px-3">
                                    <div class="bg-red-100 text-red-700 px-2 py-1 rounded inline-block text-xs">
                                        <div class="font-medium">
                                            <?php echo date("M j", strtotime($appointment["DateTime"])); ?>
                                        </div>
                                        <div class="font-bold">
                                            <?php echo date("g:i A", strtotime($appointment["DateTime"])); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="font-medium text-gray-900 text-sm">
                                        <?php echo htmlspecialchars($appointment["PatientFirstName"] . " " . $appointment["PatientLastName"]); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">ID #<?php echo str_pad($appointment["AppointmentID"], 4, "0", STR_PAD_LEFT); ?></div>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="text-sm text-gray-600">
                                        <?php echo htmlspecialchars($appointment["PatientEmail"]); ?>
                                    </div>
                                </td>
                                <td class="py-2 px-3">
                                    <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                        <?php echo htmlspecialchars($appointment["AppointmentType"]); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-3 max-w-xs">
                                    <?php if (!empty($appointment["Reason"])): ?>
                                        <div class="text-sm text-gray-600 truncate" title="<?php echo htmlspecialchars($appointment["Reason"]); ?>">
                                            <?php echo htmlspecialchars($appointment["Reason"]); ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-sm italic">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-3">
                                    <div class="flex space-x-1">
                                        <form method="POST" action="<?php echo BASE_URL; ?>/doctor/approve-cancellation" class="inline">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment["AppointmentID"]; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                            <button type="submit" onclick="return confirm('Are you sure you want to approve this cancellation? This action cannot be undone.')" 
                                                    class="bg-red-500/80 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition-colors">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/doctor/deny-cancellation" class="inline">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment["AppointmentID"]; ?>">
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
            
            <!-- Pagination Controls Bottom -->
            <?php echo renderPaginationControls('pending-cancellation-requests', 'bottom'); ?>
        </div>
        
        <div class="text-center mt-4 mb-4">
            <button 
                onclick="toggleTableCollapse('pending-cancellation-requests')" 
                id="collapse-btn-pending-cancellation-requests" 
                class="bg-transparent! shadow-none! text-black transition-colors duration-200 text-sm font-medium cursor-pointer">
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