<?php
$statusClass = "";
$statusText = htmlspecialchars($payment["Status"]);
switch (strtolower($payment["Status"])) {
    case "paid":
        $statusClass = "bg-green-100/40 text-green-800 border-l-green-500/70";
        break;
    case "pending":
        $statusClass = "bg-white/40 text-yellow-800 border-l-yellow-500/70";
        break;
    case "overdue":
        $statusClass = "bg-red-100/40 text-red-800 border-l-red-500/70";
        break;
    case "cancelled":
        $statusClass = "bg-gray-100/40 text-gray-800 border-l-gray-500/70";
        break;
    default:
        $statusClass = "bg-blue-100/40 text-blue-800 border-l-blue-500/70";
}

// Create unique identifiers for this section
$paymentId = $payment["PaymentID"] ?? $payment["AppointmentID"];
$sectionPrefix = $sectionPrefix ?? "default";
$uniqueId = $sectionPrefix . "-" . $paymentId;
?>

<div class="glass-card rounded-2xl shadow-sm border-l-4 border-gray-200 <?php echo $statusClass; ?> p-6 hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between cursor-pointer" onclick="togglePaymentCard('<?php echo $uniqueId; ?>')">
        <div class="flex items-center space-x-4">
            <!-- Payment ID -->
            <div class="glass-card bg-white/10 text-nhd-brown px-3 py-2 rounded-lg shadow-sm border-gray-200">
                <span class="text-xs font-medium uppercase tracking-wider block">Payment ID</span>
                <span class="text-lg font-bold font-mono"><?php if (
                    $payment["PaymentID"]
                ): ?>#<?php echo str_pad(
    $payment["PaymentID"],
    6,
    "0",
    STR_PAD_LEFT
);else: ?>-<?php endif; ?></span>
            </div>
            
            <!-- Appointment ID -->
            <div class="glass-card bg-white text-nhd-blue border-1 border-nhd-blue/20 px-3 py-2 rounded-lg shadow-sm">
                <span class="text-xs font-medium uppercase tracking-wider block">Appointment ID</span>
                <span class="text-lg font-bold font-mono">#<?php echo str_pad(
                    $payment["AppointmentID"],
                    6,
                    "0",
                    STR_PAD_LEFT
                ); ?></span>
            </div>
            
            <!-- Status -->
            <div>
                <span class="inline-block glass-card px-3 py-1 text-sm font-medium shadow-sm border-gray-200 rounded-full <?php echo explode(
                    " ",
                    $statusClass
                )[0] .
                    " " .
                    explode(" ", $statusClass)[1]; ?>">
                    <?php echo $statusText; ?>
                </span>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Price and Date -->
            <div class="text-right">
                <div class="text-2xl font-bold text-nhd-brown">
                    ₱<?php echo number_format($payment["total_amount"], 2); ?>
                </div>
                <div class="text-sm text-gray-500">
                    Updated <?php echo date(
                        "M j, Y",
                        strtotime($payment["UpdatedAt"])
                    ); ?>
                </div>
            </div>
            
            <!-- Expand/Collapse Button -->
            <div class="glass-card bg-gray-100/60 hover:bg-gray-200/60 rounded-full p-2 transition-colors">
                <svg id="expand-icon-<?php echo $uniqueId; ?>" class="w-5 h-5 text-gray-600 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
    </div>

    <div id="payment-content-<?php echo $uniqueId; ?>" class="hidden mt-6 pt-6 border-t border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div class="mb-4 lg:mb-0">
                <h3 class="text-2xl font-semibold text-nhd-brown mb-2 font-family-bodoni">
                    <?php echo htmlspecialchars($payment["AppointmentType"]); ?>
                </h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Doctor:</strong> <?php echo htmlspecialchars(
                        $payment["DoctorName"]
                    ); ?></p>
                    <p><strong>Specialization:</strong> <?php echo htmlspecialchars(
                        $payment["Specialization"]
                    ); ?></p>
                    <p><strong>Date:</strong> <?php echo date(
                        "M j, Y",
                        strtotime($payment["AppointmentDateTime"])
                    ); ?></p>
                    <p><strong>Time:</strong> <?php echo date(
                        "g:i A",
                        strtotime($payment["AppointmentDateTime"])
                    ); ?></p>
                </div>
            </div>
        </div>

        <!-- Payment Breakdown -->
        <?php if (!empty($payment["items"])): ?>
            <div class="glass-card bg-white border-2 border-gray-200 shadow-sm rounded-xl p-4 mb-4">
                <h4 class="text-lg font-medium text-nhd-brown mb-3">Payment Breakdown</h4>
                <div class="space-y-3">
                    <?php foreach ($payment["items"] as $item): ?>
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <p class="text-gray-900 font-medium"><?php echo htmlspecialchars(
                                    $item["Description"]
                                ); ?></p>
                                <?php if ($item["Quantity"] > 1): ?>
                                    <p class="text-sm text-gray-500">
                                        $<?php echo number_format(
                                            $item["Amount"],
                                            2
                                        ); ?> × <?php echo $item["Quantity"]; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-900 font-semibold">$<?php echo number_format(
                                    $item["Total"],
                                    2
                                ); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Total Line -->
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="flex justify-between items-center">
                            <p class="text-lg font-semibold text-nhd-brown">Total Amount</p>
                            <p class="text-lg font-bold text-nhd-brown">$<?php echo number_format(
                                $payment["total_amount"],
                                2
                            ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="glass-card bg-white border-1 border-gray-200 shadow-sm rounded-xl p-4 mb-4">
                <p class="text-yellow-800 text-center">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <?php if ($payment["PaymentID"]): ?>
                        Payment breakdown not available
                    <?php else: ?>
                        Payment invoice will be generated after your appointment
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Reason for Visit -->
        <?php if (!empty($payment["Reason"])): ?>
            <div class="glass-card bg-white border-1 border-gray-200 shadow-sm rounded-xl p-4 mb-4">
                <h4 class="text-lg font-medium text-nhd-brown mb-2">Reason for Visit</h4>
                <p class="text-gray-700"><?php echo nl2br(
                    htmlspecialchars($payment["Reason"])
                ); ?></p>
            </div>
        <?php endif; ?>

        <!-- Payment Notes -->
        <?php if (!empty($payment["Notes"])): ?>
            <div class="glass-card bg-green-50/50 rounded-xl p-4 mb-4">
                <h4 class="text-lg font-medium text-nhd-brown mb-2">Payment Notes</h4>
                <p class="text-gray-700"><?php echo nl2br(
                    htmlspecialchars($payment["Notes"])
                ); ?></p>
                <?php if (!empty($payment["UpdatedByName"])): ?>
                    <p class="text-sm text-gray-500 mt-2">
                        - Updated by <?php echo htmlspecialchars(
                            $payment["UpdatedByName"]
                        ); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <?php if ($payment["PaymentID"]): ?>
                <button onclick="viewPaymentDetails(<?php echo $payment[
                    "PaymentID"
                ]; ?>)" 
                        class="flex-1 px-6 py-3 glass-card bg-nhd-blue/85 text-white rounded-2xl hover:bg-nhd-blue transition-colors font-medium">
                    View Details
                </button>
                <button onclick="printInvoice(<?php echo $payment[
                    "PaymentID"
                ]; ?>)" 
                        class="flex-1 px-6 py-3 glass-card bg-nhd-brown/85 text-white rounded-2xl hover:bg-nhd-brown transition-colors font-medium">
                    Print Invoice
                </button>
            <?php else: ?>
                <div class="flex-1 px-6 py-3 glass-card bg-white text-yellow-500 shadow-sm border-yellow-400 border-2 rounded-2xl text-center">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    Invoice pending
                </div>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>/patient/bookings/<?php echo $user[
    "id"
] ?? 1; ?>/<?php echo $payment["AppointmentID"]; ?>" 
               class="flex-1 px-6 py-3 glass-card bg-nhd-blue/80 text-white hover:bg-nhd-blue/90 rounded-2xl transition-colors font-medium text-center">
                View Appointment
            </a>
        </div>
    </div>
</div>

<!-- Script moved to main page to avoid duplication --> 