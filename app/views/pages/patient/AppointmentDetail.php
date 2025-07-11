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
        case "pending cancellation":
            return "bg-purple-100/60 text-purple-800";
        default:
            return "bg-gray-100/60 text-gray-600";
    }
} ?>

<div class="pb-6">
    <div class="px-4 mb-4">
        <div class="flex items-center mb-3 justify-between">
            <div class="flex flex-row items-center">
                <a href="<?php echo BASE_URL; ?>/patient/bookings" 
                class="glass-card bg-gray-100/80 hover:bg-gray-200/80 rounded-full p-2 transition-colors mr-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-nhd-brown mb-1 font-family-bodoni tracking-tight">Appointment Details</h2>
                    <p class="text-gray-600 text-sm">View your appointment information and medical report</p>
                </div>
            </div>
            <div>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h3 class="text-xl font-semibold text-nhd-brown mb-2 md:mb-0 font-family-bodoni tracking-tighter"></h3>
                <div class="glass-card bg-white border-nhd-blue/20 border-1 text-nhd-blue px-4 py-2 rounded-xl shadow-sm">
                    <span class="text-xs font-medium uppercase tracking-wider block text-center">ID</span>
                    <span class="text-2xl font-bold font-mono tracking-wider">#<?php echo str_pad(
                        $appointment["AppointmentID"],
                        6,
                        "0",
                        STR_PAD_LEFT
                    ); ?></span>
                </div>
            </div>
            </div>
        </div>

        <!-- Status Messages for Pending Cancellation or Cancelled -->
        <?php if ($appointment["Status"] === "Pending Cancellation"): ?>
            <div class="rounded-xl bg-purple-50/60 border border-purple-200 p-4 mb-4">
                <div class="flex items-center text-purple-800 mb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Cancellation request submitted</span>
                </div>
                <p class="text-purple-700 text-sm mb-2">
                    Your appointment cancellation request has been submitted and is awaiting approval from Dr. <?php echo htmlspecialchars(
                        $appointment["DoctorFirstName"] .
                            " " .
                            $appointment["DoctorLastName"]
                    ); ?>. 
                    You will be notified once the doctor reviews your request.
                </p>
                <div class="bg-white/50 p-3 rounded-lg">
                    <div class="flex items-center text-sm text-purple-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><strong>Note:</strong> Until approved, this appointment remains scheduled. Please attend if the cancellation is not approved in time.</span>
                    </div>
                </div>
            </div>
        <?php elseif ($appointment["Status"] === "Cancelled"): ?>
            <div class="rounded-xl bg-orange-50/60 border border-orange-200 p-4 mb-4">
                <div class="flex items-center text-orange-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                    </svg>
                    <span class="font-medium">This appointment has been cancelled</span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="px-4 space-y-4">
        <!-- Appointment Information Card -->
        <div>
        
            
            <div class="flex flex-wrap gap-4">
                <!-- Appointment Type -->
                <div class="glass-card shadow-sm bg-gray-50/50 border border-gray-200 rounded-xl p-3 flex-1 min-w-[200px]">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment Type</label>
                    <p class="text-base font-semibold text-gray-900 mt-1"><?php echo htmlspecialchars(
                        $appointment["AppointmentType"]
                    ); ?></p>
                </div>

                <!-- Doctor Info -->
                <div class="glass-card shadow-sm bg-gray-50/50 border border-gray-200 rounded-xl p-3 flex-1 min-w-[200px]">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</label>
                    <p class="text-base text-gray-900 mt-1">
                        Dr. <?php echo htmlspecialchars(
                            $appointment["DoctorFirstName"] .
                                " " .
                                $appointment["DoctorLastName"]
                        ); ?>
                    </p>
                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars(
                        $appointment["Specialization"]
                    ); ?></p>
                </div>

                <!-- Date & Time -->
                <div class="glass-card shadow-sm bg-gray-50/50 border border-gray-200 rounded-xl p-3 flex-1 min-w-[200px]">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</label>
                    <p class="text-base font-semibold text-gray-900 mt-1">
                        <?php echo date(
                            "M j, Y",
                            strtotime($appointment["DateTime"])
                        ); ?>
                    </p>
                    <p class="text-xs text-gray-600">
                        <?php echo date(
                            "g:i A",
                            strtotime($appointment["DateTime"])
                        ); ?>
                    </p>
                </div>

                <!-- Status -->
                <div class="glass-card shadow-sm bg-gray-50/50 border border-gray-200 rounded-xl p-3 flex-1 min-w-[150px] <?php echo getAppointmentStatusClass($appointment["Status"]); ?>">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</label>
                    <div class="mt-1">
                        <span class="inline-block glass-card px-2 py-1 text-xs font-medium rounded-full w-fit <?php echo getAppointmentStatusClass(
                            $appointment["Status"]
                        ); ?>">
                            <?php echo htmlspecialchars(
                                $appointment["Status"]
                            ); ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 mt-4">
                <!-- Reason for Visit -->
                <div class="glass-card shadow-sm bg-gray-50/50 border border-gray-200 rounded-xl p-3 flex-1 min-w-[300px]">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Reason for Visit</label>
                    <p class="text-gray-900 text-sm mt-1"><?php echo htmlspecialchars(
                        $appointment["Reason"]
                    ); ?></p>
                </div>

                <!-- Booked On -->
                <div class="glass-card shadow-sm bg-gray-50/50 border border-gray-200 rounded-xl p-3 flex-1 min-w-[200px]">
                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Booked On</label>
                    <p class="text-gray-600 text-sm mt-1">
                        <?php echo date(
                            "M j, Y g:i A",
                            strtotime($appointment["CreatedAt"])
                        ); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Medical Report Card -->
        <div class="glass-card rounded-l-none rounded-r-2xl shadow-sm border-l-4 border-l-green-500/70 p-5 border-2 border-gray-200">
            <h3 class="text-xl font-semibold text-nhd-brown mb-4 font-family-bodoni tracking-tighter">Medical Report</h3>
            
            <?php if ($appointmentReport): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <!-- Vital Signs -->
                    <div class="glass-card border-gray-200 border-2 rounded-xl p-4 shadow-md">
                        <h4 class="text-base font-medium text-nhd-brown mb-3">Vital Signs</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Diagnosis</label>
                                <p class="text-gray-900 font-medium text-sm">
                                    <?php echo $appointmentReport["Diagnosis"]
                                        ? htmlspecialchars(
                                            $appointmentReport["Diagnosis"]
                                        )
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Oral Notes</label>
                                <p class="text-gray-900 font-medium text-sm">
                                    <?php echo $appointmentReport["OralNotes"]
                                        ? $appointmentReport["OralNotes"] . ""
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">X-Ray Images (if Applicable)</label>
                                <p class="text-gray-900 font-medium text-sm">
                                    <?php echo $appointmentReport["XrayImages"]
                                        ? $appointmentReport["XrayImages"] . ""
                                        : "Not recorded"; ?>
                                </p>
                            </div>  
                        </div>
                    </div>

                    <!-- Patient Physical Info -->
                    <?php if ($patientRecord): ?>
                    <div class="glass-card border-gray-200 border-2 rounded-xl p-4 shadow-md">
                        <h4 class="text-base font-medium text-nhd-brown mb-3">Physical Information</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Height</label>
                                <p class="text-gray-900 font-medium text-sm">
                                    <?php echo $patientRecord["height"]
                                        ? $patientRecord["height"] . " cm"
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">Weight</label>
                                <p class="text-gray-900 font-medium text-sm">
                                    <?php echo $patientRecord["weight"]
                                        ? $patientRecord["weight"] . " kg"
                                        : "Not recorded"; ?>
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-sm font-medium text-gray-500">BMI</label>
                                <p class="text-gray-900 font-medium text-sm">
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
                    <div class="glass-card border-gray-200 border-2 rounded-xl p-4 shadow-md">
                        <h4 class="text-base font-medium text-nhd-brown mb-3">Report Information</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Report Created</label>
                                <p class="text-gray-900 font-medium text-sm">
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
                <?php if ($appointmentReport["OralNotes"]): ?>
                <div class="glass-card bg-gray-50/50 border-gray-200 border-2 rounded-xl p-4 shadow-md">
                    <h4 class="text-base font-medium text-nhd-brown mb-3">General Appearance & Notes</h4>
                    <p class="text-gray-900 leading-relaxed text-sm">
                        <?php echo nl2br(
                            htmlspecialchars($appointmentReport["OralNotes"])
                        ); ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Allergies Section -->
                <?php if ($patientRecord && $patientRecord["allergies"]): ?>
                <div class="glass-card border-gray-200 border-2 rounded-xl p-4 mt-3 shadow-md">
                    <h4 class="text-base font-medium text-red-800 mb-3">⚠️ Known Allergies</h4>
                    <p class="text-red-900 text-sm">
                        <?php echo nl2br(
                            htmlspecialchars($patientRecord["allergies"])
                        ); ?>
                    </p>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="glass-card bg-gray-50/50 rounded-xl p-6 text-center">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h4 class="text-base font-medium text-gray-600 mb-2">No Medical Report Available</h4>
                    <p class="text-gray-500 text-sm">
                        <?php if (
                            in_array($appointment["Status"], [
                                "Pending",
                                "Approved",
                                "Rescheduled",
                            ])
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
        <div class="glass-card bg-white/80 rounded-2xl shadow-md border-2 border-nhd-brown/20 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Payment Information</h3>
                    <div class="flex items-center space-x-4">
                        <div class="glass-card bg-nhd-brown/10 shadow-md text-nhd-brown px-3 py-2 rounded-lg">
                            <span class="text-xs font-medium uppercase tracking-wider block">Payment ID</span>
                            <span class="text-xl font-bold font-mono"><?php if (
                                $appointmentPayment
                            ): ?>#<?php echo str_pad(
                                $appointmentPayment["PaymentID"],
                                6,
                                "0",
                                STR_PAD_LEFT
                            );
                            else: ?>-<?php endif; ?></span>
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
                    <span class="text-xl font-bold text-nhd-brown">₱<?php echo $appointmentPayment
                        ? number_format(
                            $appointmentPayment["total_amount"] ?? 0,
                            2
                        )
                        : "0.00"; ?></span>
                    <?php if ($appointmentPayment): ?>
                    <div class="mt-2">
                        <a onclick="viewPaymentDetails(<?php echo $appointmentPayment[
                            "PaymentID"
                        ]; ?>)"
                           class="inline-flex items-center px-3 py-1 glass-card bg-nhd-blue/85 text-white text-sm rounded-xl hover:bg-nhd-blue transition-colors hover:cursor-pointer">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            View Details
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!$appointmentPayment): ?>
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <div class="glass-card bg-nhd-brown/15 shadow-md border-1 rounded-xl p-3">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <p class="text-yellow-800 text-sm">
                                <?php if (
                                    in_array($appointment["Status"], [
                                        "Pending",
                                        "Approved",
                                        "Rescheduled",
                                    ])
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
        <div class="pt-2 border-t border-gray-200">
            <div class="flex flex-wrap gap-2 items-center justify-end">
                <!-- Back to Bookings -->
                <a href="<?php echo BASE_URL; ?>/patient/bookings" 
                   class="px-4 py-2 glass-card bg-gray-100/80 text-gray-700 shadow-sm border-gray-200 border-1 rounded-xl hover:bg-gray-200/80 transition-colors font-medium text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Bookings
                </a>
                
                <!-- Print Button -->
                <button onclick="window.print()" 
                        class="px-4 py-2 glass-card bg-nhd-brown/85 text-white rounded-xl hover:bg-nhd-brown transition-colors font-medium text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <?php if (
                        in_array($appointment["Status"], [
                            "Pending",
                            "Approved",
                            "Rescheduled",
                        ])
                    ): ?>
                        Print Info
                    <?php else: ?>
                        Print Report
                    <?php endif; ?>
                </button>

                <!-- Reschedule Button (only for active appointments) -->
                <?php if (
                    in_array($appointment["Status"], [
                        "Pending",
                        "Approved",
                        "Rescheduled",
                    ])
                ): ?>
                    <button onclick="openRescheduleModal(<?php echo $appointment[
                        "AppointmentID"
                    ]; ?>, <?php echo $appointment[
    "DoctorID"
]; ?>, '<?php echo date(
    "Y-m-d",
    strtotime($appointment["DateTime"])
); ?>', '<?php echo date("H:i", strtotime($appointment["DateTime"])); ?>')" 
                            class="px-4 py-2 glass-card bg-nhd-blue/85 text-white rounded-xl hover:bg-nhd-blue transition-colors font-medium text-sm">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Reschedule
                    </button>
                    
                    <!-- Cancel Button -->
                    <?php if ($appointmentPayment && $appointmentPayment["Status"] !== "Cancelled"): ?>
                        <div class="px-4 py-2 glass-card shadow-sm bg-gray-300/85 text-gray-600 rounded-xl font-medium cursor-not-allowed text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Cannot Cancel
                        </div>
                    <?php else: ?>
                        <button onclick="confirmCancel(<?php echo $appointment[
                            "AppointmentID"
                        ]; ?>)" 
                                class="px-4 py-2 glass-card bg-red-500/85 text-white rounded-xl hover:bg-red-600 transition-colors font-medium text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <!-- Payment restriction notice -->
            <?php if ($appointmentPayment && $appointmentPayment["Status"] !== "Cancelled" && in_array($appointment["Status"], ["Pending", "Approved", "Rescheduled"])): ?>
                <div class="mt-3 p-3 bg-orange-50/60 border border-orange-200 rounded-xl">
                    <div class="flex items-start text-orange-800 text-sm">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>This appointment cannot be cancelled because payment has already been processed. Contact the clinic for assistance with refunds or rescheduling.</span>
                    </div>
                </div>
            <?php endif; ?>
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