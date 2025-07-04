<div class="pb-8">
    <div class="px-4 mb-6">
        <div class="flex items-center mb-4">
            <a href="<?php echo BASE_URL; ?>/patient/bookings" 
               class="glass-card bg-gray-100/80 hover:bg-gray-200/80 rounded-full p-2 transition-colors mr-4">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Appointment Details</h2>
                <p class="text-gray-600">View your appointment information and medical report</p>
            </div>
        </div>
        <!-- Upcoming appointment actions -->
        <?php if (strtotime($appointment["DateTime"]) > time()): ?>
                <div class="rounded-xl">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="openRescheduleModal(<?php echo $appointment[
                            "AppointmentID"
                        ]; ?>, <?php echo $appointment[
    "DoctorID"
]; ?>, '<?php echo date(
    "Y-m-d",
    strtotime($appointment["DateTime"])
); ?>', '<?php echo date("H:i", strtotime($appointment["DateTime"])); ?>')" 
                                class="flex-1 px-6 py-3 glass-card bg-nhd-blue/85 text-white rounded-2xl hover:bg-nhd-blue transition-colors font-medium">
                            Reschedule Appointment
                        </button>
                        <button onclick="confirmCancel(<?php echo $appointment[
                            "AppointmentID"
                        ]; ?>)" 
                                class="flex-1 px-6 py-3 glass-card bg-red-500/85 text-white rounded-2xl hover:bg-red-600 transition-colors font-medium">
                            Cancel Appointment
                        </button>
                    </div>
                </div>
            <?php endif; ?>
    </div>

    <div class="px-4 space-y-6">
        <!-- Appointment Information Card -->
        <div class="glass-card rounded-l-none rounded-r-2xl shadow-sm border-2 border-gray-200 border-l-4 border-l-nhd-blue/70 p-6">
        <div class="text-center mb-4">
                <h3 class="text-xl font-semibold text-nhd-blue mb-4 font-family-sans">Appointment ID</h3>
                <div class="glass-card bg-white border-nhd-blue/20 border-1 text-nhd-blue px-6 py-4 rounded-2xl inline-block shadow-sm">
                    <span class="text-4xl font-bold font-mono tracking-wider">#<?php echo str_pad(
                        $appointment["AppointmentID"],
                        6,
                        "0",
                        STR_PAD_LEFT
                    ); ?></span>
                </div>
            </div>
            <h3 class="text-2xl font-semibold text-nhd-brown mb-4 font-family-bodoni tracking-tighter">Appointment Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Appointment Type</label>
                        <p class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars(
                            $appointment["AppointmentType"]
                        ); ?></p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Doctor</label>
                        <p class="text-lg text-gray-900">
                            Dr. <?php echo htmlspecialchars(
                                $appointment["DoctorFirstName"] .
                                    " " .
                                    $appointment["DoctorLastName"]
                            ); ?>
                        </p>
                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars(
                            $appointment["Specialization"]
                        ); ?></p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Reason for Visit</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars(
                            $appointment["Reason"]
                        ); ?></p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date & Time</label>
                        <p class="text-lg font-semibold text-gray-900">
                            <?php echo date(
                                "M j, Y",
                                strtotime($appointment["DateTime"])
                            ); ?>
                        </p>
                        <p class="text-gray-600">
                            <?php echo date(
                                "g:i A",
                                strtotime($appointment["DateTime"])
                            ); ?>
                        </p>
                    </div>
                    
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <?php if (
                            strtotime($appointment["DateTime"]) > time()
                        ): ?>
                            <span class="inline-block glass-card border-green-100/40 border-1 shadow-md px-3 py-1 text-sm font-medium rounded-full bg-green-100/40 text-green-800">
                                Upcoming
                            </span>
                        <?php else: ?>
                            <span class="inline-block glass-card px-3 py-1 bg-nhd-green/70 text-black border-green-100/40 border-1 shadow-md text-sm font-medium rounded-full">
                                Completed
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Booked On</label>
                        <p class="text-gray-600">
                            <?php echo date(
                                "M j, Y g:i A",
                                strtotime($appointment["CreatedAt"])
                            ); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Report Card -->
        <div class="glass-card rounded-l-none rounded-r-2xl shadow-sm border-l-4 border-l-green-500/70 p-6 border-2 border-gray-200">
            <h3 class="text-2xl font-semibold text-nhd-brown mb-4 font-family-bodoni tracking-tighter">Medical Report</h3>
            
            <?php if ($appointmentReport): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Vital Signs -->
                    <div class="glass-card border-gray-200 border-2 rounded-2xl p-4 shadow-md">
                        <h4 class="text-lg font-medium text-nhd-brown mb-3">Vital Signs</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Diagnosis</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo $appointmentReport["Diagnosis"]
                                        ? htmlspecialchars(
                                            $appointmentReport["Diagnosis"]
                                        )
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Oral Notes</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo $appointmentReport["OralNotes"]
                                        ? $appointmentReport["OralNotes"] . ""
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">X-Ray Images (if Applicable)</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo $appointmentReport["XrayImages"]
                                        ? $appointmentReport["XrayImages"] . ""
                                        : "Not recorded"; ?>
                                </p>
                            </div>  
                        </div>
                    </div>

                    <!-- Patient Physical Info -->
                    <?php if ($patientRecord): ?>
                    <div class="glass-card border-gray-200 border-2 rounded-2xl p-4 shadow-md">
                        <h4 class="text-lg font-medium text-nhd-brown mb-3">Physical Information</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Height</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo $patientRecord["height"]
                                        ? $patientRecord["height"] . " cm"
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Weight</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo $patientRecord["weight"]
                                        ? $patientRecord["weight"] . " kg"
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">BMI</label>
                                <p class="text-gray-900 font-medium">
                                    <?php if (
                                        $patientRecord["height"] &&
                                        $patientRecord["weight"]
                                    ) {
                                        $heightInM =
                                            $patientRecord["height"] / 100;
                                        $bmi =
                                            $patientRecord["weight"] /
                                            ($heightInM * $heightInM);
                                        echo number_format($bmi, 1);
                                    } else {
                                        echo "Not available";
                                    } ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Report Details -->
                    <div class="glass-card border-gray-200 border-2 rounded-2xl p-4 shadow-md">
                        <h4 class="text-lg font-medium text-nhd-brown mb-3">Report Information</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Report Created</label>
                                <p class="text-gray-900 font-medium">
                                    <?php echo date(
                                        "M j, Y g:i A",
                                        strtotime(
                                            $appointmentReport["CreatedAt"]
                                        )
                                    ); ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Report ID</label>
                                <p class="text-gray-600 text-sm font-mono">
                                    #<?php echo str_pad(
                                        $appointmentReport[
                                            "AppointmentReportID"
                                        ],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- General Appearance and Notes -->
                <?php if ($appointmentReport["GeneralAppearance"]): ?>
                <div class="glass-card bg-gray-50/50 border-gray-200 border-2 rounded-2xl p-4 shadow-md">
                    <h4 class="text-lg font-medium text-nhd-brown mb-3">General Appearance & Notes</h4>
                    <p class="text-gray-900 leading-relaxed">
                        <?php echo nl2br(
                            htmlspecialchars(
                                $appointmentReport["GeneralAppearance"]
                            )
                        ); ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Allergies Section -->
                <?php if ($patientRecord && $patientRecord["allergies"]): ?>
                <div class="glass-card border-gray-200 border-2 rounded-2xl p-4 mt-4 shadow-md">
                    <h4 class="text-lg font-medium text-red-800 mb-3">⚠️ Known Allergies</h4>
                    <p class="text-red-900">
                        <?php echo nl2br(
                            htmlspecialchars($patientRecord["allergies"])
                        ); ?>
                    </p>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="glass-card bg-gray-50/50 rounded-xl p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h4 class="text-lg font-medium text-gray-600 mb-2">No Medical Report Available</h4>
                    <p class="text-gray-500">
                        <?php if (
                            strtotime($appointment["DateTime"]) > time()
                        ): ?>
                            Medical report will be available after your appointment.
                        <?php else: ?>
                            No medical report was generated for this appointment.
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Payment Information Section -->
        <div class="glass-card bg-white/80 rounded-2xl shadow-md border-2 border-nhd-brown/20 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Payment Information</h3>
                    <div class="flex items-center space-x-4">
                        <div class="glass-card bg-nhd-brown/10 shadow-md text-nhd-brown px-4 py-2 rounded-lg">
                            <span class="text-xs font-medium uppercase tracking-wider block">Payment ID</span>
                            <span class="text-2xl font-bold font-mono"><?php if (
                                $appointmentPayment
                            ): ?>#<?php echo str_pad(
    $appointmentPayment["PaymentID"],
    6,
    "0",
    STR_PAD_LEFT
);else: ?>-<?php endif; ?></span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block">Status</span>
                            <span class="inline-block glass-card shadow-md px-3 py-1 text-sm font-medium rounded-full 
                                <?php if ($appointmentPayment): ?>
                                    <?php echo strtolower(
                                        $appointmentPayment["Status"]
                                    ) === "paid"
                                        ? "bg-green-100/40 text-green-800 border-green-100"
                                        : "bg-yellow-100/40 text-yellow-800 border-yellow-400"; ?>">
                                    <?php echo htmlspecialchars(
                                        $appointmentPayment["Status"]
                                    ); ?>
                                <?php else: ?>
                                    bg-yellow-100/40 text-yellow-800">
                                    Pending
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm text-gray-500 block">Amount</span>
                    <span class="text-2xl font-bold text-nhd-brown">₱<?php echo $appointmentPayment
                        ? number_format(
                            $appointmentPayment["total_amount"] ?? 0,
                            2
                        )
                        : "0.00"; ?></span>
                    <div class="mt-2">
                        <a onclick="viewPaymentDetails(<?php echo $appointmentPayment[
                            "PaymentID"
                        ]; ?>)"
                           class="inline-flex items-center px-3 py-1 glass-card bg-nhd-blue/85 text-white text-sm rounded-xl hover:bg-nhd-blue transition-colors hover:cursor-pointer">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            View Payment Details
                        </a>
                    </div>
                </div>
            </div>
            <?php if (!$appointmentPayment): ?>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="glass-card bg-nhd-brown/15 shadow-md border-1 rounded-xl p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-yellow-800 text-sm">
                                <?php if (
                                    strtotime($appointment["DateTime"]) > time()
                                ): ?>
                                    Payment invoice will be generated after your appointment.
                                <?php else: ?>
                                    Payment processing in progress. Invoice will be available soon.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            
            <!-- General actions -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?php echo BASE_URL; ?>/patient/bookings" 
                   class="flex-1 px-6 py-3 glass-card bg-gray-100/80 text-gray-700 shadow-sm border-gray-200 border-1 rounded-2xl hover:bg-gray-200/80 transition-colors font-medium text-center">
                    Back to Bookings
                </a>
                
                <?php if (strtotime($appointment["DateTime"]) > time()): ?>
                    <button onclick="window.print()" 
                            class="flex-1 px-6 py-3 glass-card bg-nhd-brown/85 text-white rounded-2xl hover:bg-nhd-brown transition-colors font-medium">
                        Print Appointment Info
                    </button>
                <?php else: ?>
                    <button onclick="window.print()" 
                            class="flex-1 px-6 py-3 glass-card bg-nhd-brown/85 text-white rounded-2xl hover:bg-nhd-brown transition-colors font-medium">
                        Print Medical Report
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .glass-card {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    button, .hover\:bg-gray-200\/80 {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
}
</style>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="fixed inset-0 bg-black/30 backdrop-blur-[1px] hidden items-center justify-center z-50 p-4">
    <div class="glass-card bg-white/90 backdrop-blur-sm rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-semibold text-nhd-brown font-family-bodoni">Reschedule Appointment</h3>
            <button type="button" onclick="closeRescheduleModal()" 
                    class="glass-card bg-gray-100/80 hover:bg-gray-200/80 rounded-full p-2 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="rescheduleForm" method="POST" action="<?php echo BASE_URL; ?>/patient/reschedule-appointment">
            <input type="hidden" name="appointment_id" id="reschedule_appointment_id" value="<?php echo $appointment[
                "AppointmentID"
            ]; ?>">
            <input type="hidden" name="doctor_id" id="reschedule_doctor_id" value="<?php echo $appointment[
                "DoctorID"
            ]; ?>">
            <input type="hidden" name="new_appointment_date" id="reschedule_new_date">
            <input type="hidden" name="new_appointment_time" id="reschedule_new_time">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[
                "csrf_token"
            ] ?? ""; ?>">
            
            <!-- Current Appointment Info -->
            <div class="glass-card bg-blue-50/50 rounded-2xl p-4 mb-6">
                <h4 class="text-lg font-medium text-nhd-brown mb-2">Current Appointment</h4>
                <div id="current-appointment-info" class="text-sm text-gray-600">
                    <p><strong>Type:</strong> <?php echo htmlspecialchars(
                        $appointment["AppointmentType"]
                    ); ?></p>
                    <p><strong>Doctor:</strong> Dr. <?php echo htmlspecialchars(
                        $appointment["DoctorFirstName"] .
                            " " .
                            $appointment["DoctorLastName"]
                    ); ?></p>
                    <p><strong>Current Date:</strong> <?php echo date(
                        "l, F j, Y",
                        strtotime($appointment["DateTime"])
                    ); ?></p>
                    <p><strong>Current Time:</strong> <?php echo date(
                        "g:i A",
                        strtotime($appointment["DateTime"])
                    ); ?></p>
                </div>
            </div>
            
            <!-- Date & Time Selection -->
            <div class="mb-6">
                <label class="block text-xl font-medium tracking-tight font-family-bodoni text-gray-700 mb-4">Select New Date & Time</label>
                
                <div class="flex flex-col lg:flex-row gap-6 w-full">
                    <!-- Calendar Section -->
                    <div class="flex-shrink-0">
                        <h3 class="text-base font-medium text-gray-800 mb-3">Choose Date</h3>
                        <div class="w-[320px] glass-card border border-gray-200 rounded-xl shadow-sm p-4">
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" id="reschedule-prev-month" class="glass-card bg-nhd-blue/80 text-white rounded-lg p-2 hover:bg-nhd-blue transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <h3 id="reschedule-calendar-month-year" class="text-lg font-semibold text-gray-800"></h3>
                                <button type="button" id="reschedule-next-month" class="glass-card bg-nhd-blue/80 text-white rounded-lg p-2 hover:bg-nhd-blue transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Days of Week -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Sun</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Mon</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Tue</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Wed</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Thu</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Fri</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Sat</div>
                            </div>
                            
                            <!-- Calendar Days -->
                            <div id="reschedule-calendar-days" class="grid grid-cols-7 gap-1">
                                <!-- Days will be generated by JavaScript -->
                            </div>
                            
                            <!-- Selected Date Display -->
                            <div class="mt-4 p-3 glass-card bg-gray-50/50 rounded-2xl">
                                <p class="text-sm text-gray-600">Selected Date:</p>
                                <p id="reschedule-selected-date-display" class="text-base font-medium text-nhd-brown">No date selected</p>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots Section -->
                    <div class="flex flex-col flex-1 gap-4">
                        <h3 class="text-base font-medium text-gray-800 mb-3">Choose Time</h3>
                        <div class="glass-card bg-gray-50/30 rounded-2xl p-4">
                            <div id="reschedule-time-slots-container" class="flex flex-wrap gap-3 w-full min-h-[120px]">
                                <div class="w-full text-center py-8">
                                    <p class="text-gray-500 text-sm">Please select a date to view available time slots.</p>
                                </div>
                            </div>
                            
                            <!-- Selected Time Display -->
                            <div class="mt-4 p-3 glass-card bg-white/50 rounded-2xl">
                                <p class="text-sm text-gray-600">Selected Time:</p>
                                <p id="reschedule-selected-time-display" class="text-base font-medium text-nhd-brown">No time selected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="button" onclick="closeRescheduleModal()" 
                        class="flex-1 px-6 py-3 glass-card bg-gray-100/80 text-gray-700 rounded-2xl hover:bg-gray-200/80 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" id="reschedule-submit-btn" disabled
                        class="flex-1 px-6 py-3 glass-card bg-nhd-brown/85 text-white rounded-2xl hover:bg-nhd-brown transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    Reschedule Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black/30 backdrop-blur-[1px] hidden items-center justify-center z-50">
    <div class="bg-white/80 backdrop-blur-sm glass-card rounded-2xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold mb-4">Cancel Appointment</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
        
        <form id="cancelForm" method="POST" action="<?php echo BASE_URL; ?>/patient/cancel-appointment">
            <input type="hidden" name="appointment_id" id="cancel_appointment_id" value="<?php echo $appointment[
                "AppointmentID"
            ]; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION[
                "csrf_token"
            ] ?? ""; ?>">
            
            <div class="flex space-x-3">
                <button type="button" onclick="closeCancelModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-2xl glass-card text-gray-900 hover:bg-gray-200">
                    Keep Appointment
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-500/60 rounded-2xl glass-card text-white hover:bg-red-600/70 transition-colors">
                    Cancel Appointment
                </button>
            </div>
        </form>
    </div>
</div>
<div id="paymentDetailsModal" class="fixed inset-0 bg-black/30 backdrop-blur-[1px] hidden items-center justify-center z-50 p-4">
    <div class="glass-card bg-white/90 backdrop-blur-sm rounded-2xl p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-semibold text-nhd-brown font-family-bodoni">Payment Details</h3>
            <button type="button" onclick="closePaymentModal()" 
                    class="glass-card bg-gray-100/80 hover:bg-gray-200/80 rounded-full p-2 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div id="paymentDetailsContent">
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-nhd-blue mx-auto"></div>
                <p class="text-gray-600 mt-4">Loading payment details...</p>
            </div>
        </div>
    </div>
</div>

 <script>
    function viewPaymentDetails(paymentId) {
    const modal = document.getElementById('paymentDetailsModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch(`<?php echo BASE_URL; ?>/patient/get-payment-details?payment_id=${paymentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('paymentDetailsContent').innerHTML = data.html;
            } else {
                document.getElementById('paymentDetailsContent').innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-red-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600">${data.message || 'Failed to load payment details'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('paymentDetailsContent').innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-600 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600">Network error. Please try again.</p>
                </div>
            `;
        });
}

function closePaymentModal() {
    const modal = document.getElementById('paymentDetailsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
 </script>