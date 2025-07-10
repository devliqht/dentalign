<?php
require_once __DIR__ . '/../../../components/SortableAppointmentTable.php';

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
    <div>
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
                        Total appointments: <?php 
                        $totalAppointments = 0;
                        foreach ($appointmentHistory as $status => $appointments) {
                            $totalAppointments += count($appointments);
                        }
                        echo $totalAppointments;
                        ?>
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

    <!-- Filter Buttons -->
    <div class="flex justify-between items-center my-4">
        <div class="flex space-x-2 flex-wrap">
            <button onclick="showSection('all')" id="all-btn" class="glass-card bg-nhd-blue/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-white">
                All Appointments
            </button>
            <?php if (!empty($pendingCancellations)): ?>
                <button onclick="showSection('pending-cancellation-requests')" id="pending-cancellation-requests-btn" class="glass-card bg-red-200/80 text-sm shadow-sm px-3 py-2 rounded-2xl text-red-700">
                    Pending Cancellations (<?php echo count($pendingCancellations); ?>)
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
        
        <?php foreach (["Pending", "Approved", "Rescheduled", "Completed", "Declined", "Cancelled"] as $status): ?>
            <div class="glass-card shadow-sm bg-white/60 border-gray-200 border-1 rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold <?php echo getStatusHeaderClass($status); ?>"><?php echo count($appointmentHistory[$status] ?? []); ?></div>
                <div class="text-xs text-gray-600"><?php echo $status; ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pending Cancellation Requests Section -->
    <?php 
    if (!empty($pendingCancellations)) {
        renderPendingCancellationTable($pendingCancellations, $csrf_token);
    }
    ?>

    <!-- Appointment History Sections by Status -->
    <?php
    $statusOrder = ["Pending", "Approved", "Rescheduled", "Completed", "Declined", "Cancelled"];
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
                $sectionTitle = $status . ' Appointments <span class="text-sm font-normal text-gray-600">(' . count($appointmentHistory[$status]) . ' appointments)</span>';
                $emptyMessage = "No {$status} appointments";
                $emptyIcon = '<svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>';
                
                // Render the section using the reusable component
                renderSortableAppointmentTable(
                    $appointmentHistory[$status], 
                    [], // No payment data for doctor view
                    $sectionId, 
                    $sectionTitle, 
                    $emptyMessage, 
                    $emptyIcon, 
                    $user, 
                    false // This is doctor view, not patient view
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

<!-- Include Appointment Details Modal -->
<?php include __DIR__ . '/../../../components/SchedulePage/AppointmentDetailsModal.php'; ?>

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

/* Section visibility */
.appointment-section {
    display: none;
}

.appointment-section.active {
    display: block;
}
</style>

<script src="<?php echo BASE_URL; ?>/app/views/scripts/SortableTable.js"></script>


