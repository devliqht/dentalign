<?php
require_once __DIR__ . "/../../components/SortableAppointmentTable.php";

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
}
?>

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

    <?php
    // Upcoming Appointments Section
    $upcomingEmptyIcon = '<svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>';

renderSortableAppointmentTable(
    $upcomingAppointments,
    $appointmentPayments,
    "upcoming",
    "Upcoming Appointments",
    "No upcoming appointments",
    $upcomingEmptyIcon,
    $user
);
?>

    <?php
// Completed Appointments Section
$completedEmptyIcon = '<svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>';

renderSortableAppointmentTable(
    $completedAppointments,
    $appointmentPayments,
    "completed",
    "Completed Appointments",
    "No completed appointments yet",
    $completedEmptyIcon,
    $user
);
?>

    <?php
// Cancelled Appointments Section
$cancelledEmptyIcon = '<svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
    </svg>';

renderSortableAppointmentTable(
    $cancelledAppointments,
    $appointmentPayments,
    "cancelled",
    "Cancelled Appointments",
    "No cancelled appointments",
    $cancelledEmptyIcon,
    $user
);
?>

    <?php
// Pending Cancellation Appointments Section
$pendingCancellationEmptyIcon = '<svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>';

renderSortableAppointmentTable(
    $pendingCancellationAppointments,
    $appointmentPayments,
    "pending-cancellation",
    "Pending Cancellation",
    "No pending cancellation requests",
    $pendingCancellationEmptyIcon,
    $user
);
?>
</div>

<script src="<?php echo BASE_URL; ?>/app/views/scripts/SortableTable.js"></script>
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

function navigateToAppointment(url) {
    window.location.href = url;
}</script>

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

