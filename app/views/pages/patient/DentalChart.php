<div class="px-4 pb-8">
    <div class="pb-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Dental Chart</h2>
        <p class="text-gray-600">View your dental record and treatment history.</p>
    </div>

    <div class="bg-white rounded-2xl mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-semibold text-nhd-blue">Tooth Chart</h3>
            <div class="flex items-center space-x-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                    <span>Healthy</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                    <span>Watch</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                    <span>Treatment Needed</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-500 rounded-full mr-2"></div>
                    <span>No Data</span>
                </div>
            </div>
        </div>

        <!-- Upper Teeth -->
        <div class="mb-8">
            <h4 class="text-lg font-medium text-gray-700 mb-4 text-center">Upper Teeth</h4>
            <div class="flex justify-center">
                <div class="grid grid-cols-16 gap-1 max-w-4xl">
                    <!-- Upper Right Quadrant (1-8) -->
                    <?php for ($i = 8; $i >= 1; $i--): ?>
                        <?php
                        $toothData = $teethData[$i] ?? null;
                        $status = $toothData["Status"] ?? "";
                        $notes = $toothData["Notes"] ?? "";
                        $statusColor = "bg-gray-200";

                        if (!empty($status)) {
                            switch (strtolower($status)) {
                                case "healthy":
                                case "good":
                                    $statusColor = "bg-green-500";
                                    break;
                                case "watch":
                                case "monitoring":
                                    $statusColor = "bg-yellow-500";
                                    break;
                                case "treatment needed":
                                case "cavity":
                                case "issue":
                                    $statusColor = "bg-red-500";
                                    break;
                                default:
                                    $statusColor = "bg-blue-500";
                            }
                        }
                        ?>
                        <div class="tooth-container relative group cursor-pointer" 
                             data-tooth="<?php echo $i; ?>"
                             data-name="<?php echo htmlspecialchars(
                                 \DentalChartItem::getToothName($i)
                             ); ?>"
                             data-status="<?php echo htmlspecialchars(
                                 $status
                             ); ?>"
                             data-notes="<?php echo htmlspecialchars(
                                 $notes
                             ); ?>">
                            <div class="tooth <?php echo $statusColor; ?> w-8 h-12 rounded-t-full border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-end justify-center">
                                <span class="text-xs font-bold text-white mb-1"><?php echo $i; ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>

                    <!-- Upper Left Quadrant (9-16) -->
                    <?php for ($i = 9; $i <= 16; $i++): ?>
                        <?php
                        $toothData = $teethData[$i] ?? null;
                        $status = $toothData["Status"] ?? "";
                        $notes = $toothData["Notes"] ?? "";
                        $statusColor = "bg-gray-200";

                        if (!empty($status)) {
                            switch (strtolower($status)) {
                                case "healthy":
                                case "good":
                                    $statusColor = "bg-green-500";
                                    break;
                                case "watch":
                                case "monitoring":
                                    $statusColor = "bg-yellow-500";
                                    break;
                                case "treatment needed":
                                case "cavity":
                                case "issue":
                                    $statusColor = "bg-red-500";
                                    break;
                                default:
                                    $statusColor = "bg-blue-500";
                            }
                        }
                        ?>
                        <div class="tooth-container relative group cursor-pointer" 
                             data-tooth="<?php echo $i; ?>"
                             data-name="<?php echo htmlspecialchars(
                                 \DentalChartItem::getToothName($i)
                             ); ?>"
                             data-status="<?php echo htmlspecialchars(
                                 $status
                             ); ?>"
                             data-notes="<?php echo htmlspecialchars(
                                 $notes
                             ); ?>">
                            <div class="tooth <?php echo $statusColor; ?> w-8 h-12 rounded-t-full border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-end justify-center">
                                <span class="text-xs font-bold text-white mb-1"><?php echo $i; ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Gum Line -->
        <div class="border-t-2 border-pink-300 mb-8 relative">
            <div class="absolute left-1/2 transform -translate-x-1/2 -top-3 bg-white px-4 text-sm text-gray-500 font-medium">
                Gum Line
            </div>
        </div>

        <!-- Lower Teeth -->
        <div>
            <h4 class="text-lg font-medium text-gray-700 mb-4 text-center">Lower Teeth</h4>
            <div class="flex justify-center">
                <div class="grid grid-cols-16 gap-1 max-w-4xl">
                    <!-- Lower Right Quadrant (25-32) -->
                    <?php for ($i = 25; $i <= 32; $i++): ?>
                        <?php
                        $toothData = $teethData[$i] ?? null;
                        $status = $toothData["Status"] ?? "";
                        $notes = $toothData["Notes"] ?? "";
                        $statusColor = "bg-gray-200";

                        if (!empty($status)) {
                            switch (strtolower($status)) {
                                case "healthy":
                                case "good":
                                    $statusColor = "bg-green-500";
                                    break;
                                case "watch":
                                case "monitoring":
                                    $statusColor = "bg-yellow-500";
                                    break;
                                case "treatment needed":
                                case "cavity":
                                case "issue":
                                    $statusColor = "bg-red-500";
                                    break;
                                default:
                                    $statusColor = "bg-blue-500";
                            }
                        }
                        ?>
                        <div class="tooth-container relative group cursor-pointer" 
                             data-tooth="<?php echo $i; ?>"
                             data-name="<?php echo htmlspecialchars(
                                 \DentalChartItem::getToothName($i)
                             ); ?>"
                             data-status="<?php echo htmlspecialchars(
                                 $status
                             ); ?>"
                             data-notes="<?php echo htmlspecialchars(
                                 $notes
                             ); ?>">
                            <div class="tooth <?php echo $statusColor; ?> w-8 h-12 rounded-b-full border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-start justify-center">
                                <span class="text-xs font-bold text-white mt-1"><?php echo $i; ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>

                    <!-- Lower Left Quadrant (17-24) -->
                    <?php for ($i = 24; $i >= 17; $i--): ?>
                        <?php
                        $toothData = $teethData[$i] ?? null;
                        $status = $toothData["Status"] ?? "";
                        $notes = $toothData["Notes"] ?? "";
                        $statusColor = "bg-gray-200";

                        if (!empty($status)) {
                            switch (strtolower($status)) {
                                case "healthy":
                                case "good":
                                    $statusColor = "bg-green-500";
                                    break;
                                case "watch":
                                case "monitoring":
                                    $statusColor = "bg-yellow-500";
                                    break;
                                case "treatment needed":
                                case "cavity":
                                case "issue":
                                    $statusColor = "bg-red-500";
                                    break;
                                default:
                                    $statusColor = "bg-blue-500";
                            }
                        }
                        ?>
                        <div class="tooth-container relative group cursor-pointer" 
                             data-tooth="<?php echo $i; ?>"
                             data-name="<?php echo htmlspecialchars(
                                 \DentalChartItem::getToothName($i)
                             ); ?>"
                             data-status="<?php echo htmlspecialchars(
                                 $status
                             ); ?>"
                             data-notes="<?php echo htmlspecialchars(
                                 $notes
                             ); ?>">
                            <div class="tooth <?php echo $statusColor; ?> w-8 h-12 rounded-b-full border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-start justify-center">
                                <span class="text-xs font-bold text-white mt-1"><?php echo $i; ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tooth Information Panel -->
    <div id="tooth-info-panel" class="border-nhd-blue/50 bg-white rounded-2xl shadow-sm border-1 p-6 hidden">
        <h3 class="text-xl font-semibold text-nhd-blue mb-4">Tooth Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Basic Information</h4>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Tooth Number:</span>
                        <span id="tooth-number" class="text-gray-900 ml-2">-</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Tooth Name:</span>
                        <div id="tooth-name" class="text-gray-900 mt-1">-</div>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Status:</span>
                        <span id="tooth-status" class="ml-2 px-2 py-1 rounded-full text-xs font-medium">-</span>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Doctor's Notes</h4>
                <div id="tooth-notes" class="text-gray-900 text-sm bg-gray-50 p-3 rounded-lg min-h-[100px]">
                    No notes available
                </div>
            </div>
        </div>
    </div>

    <!-- Treatment Plans Section -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-semibold text-nhd-blue">Treatment Plans</h3>
            <?php if (!empty($treatmentPlans)): ?>
                <span class="text-sm text-gray-600"><?php echo count($treatmentPlans); ?> treatment plan(s)</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($treatmentPlans)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($treatmentPlans as $plan): ?>
                    <div class="glass-card bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer"
                         onclick="viewTreatmentPlan(<?php echo $plan['TreatmentPlanID']; ?>)">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-medium px-2 py-1 rounded-full 
                                <?php
                                switch ($plan['Status']) {
                                    case 'completed': echo 'bg-green-100 text-green-800';
                                        break;
                                    case 'in_progress': echo 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'pending': echo 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'cancelled': echo 'bg-red-100 text-red-800';
                                        break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                    ?>">
                                <?php echo ucfirst($plan['Status']); ?>
                            </span>
                            <span class="text-xs text-gray-500">
                                #<?php echo str_pad($plan['TreatmentPlanID'], 4, '0', STR_PAD_LEFT); ?>
                            </span>
                        </div>
                        
                        <h4 class="font-semibold text-gray-900 mb-2">
                            Dr. <?php echo htmlspecialchars($plan['DoctorName']); ?>
                        </h4>
                        
                        <div class="text-sm text-gray-600 mb-3">
                            <?php echo date('M j, Y', strtotime($plan['AssignedAt'])); ?>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                <span>Progress</span>
                                <span><?php echo $plan['progress']; ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-nhd-blue h-2 rounded-full transition-all duration-300" 
                                     style="width: <?php echo $plan['progress']; ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="text-xs text-gray-500">
                            <?php echo $plan['completedItems']; ?> of <?php echo $plan['totalItems']; ?> items completed
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card bg-gray-50/50 rounded-2xl p-8 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">No treatment plans found</p>
                <p class="text-sm text-gray-400">Treatment plans will appear here when your doctor creates them after appointments.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Treatment Plan Details Modal -->
<div id="treatmentPlanModal" class="fixed inset-0 bg-black/30 backdrop-blur-[1px] hidden items-center justify-center z-50 p-4">
    <div class="glass-card bg-white/90 backdrop-blur-sm rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-2xl font-semibold text-nhd-brown font-family-bodoni">Treatment Plan Details</h3>
            <button type="button" onclick="closeTreatmentPlanModal()" 
                    class="glass-card bg-gray-100/80 hover:bg-gray-200/80 rounded-full p-2 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
            <div id="treatmentPlanContent" class="p-6">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-nhd-blue mx-auto"></div>
                    <p class="text-gray-600 mt-4">Loading treatment plan details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hover Tooltip -->
<div id="tooth-tooltip" class="absolute z-50 bg-gray-800 text-white text-sm rounded px-2 py-1 hidden transition-opacity duration-200">
    <div id="tooltip-content"></div>
</div>

<script src="<?php echo BASE_URL; ?>/app/views/scripts/DentalChart/DentalChartPatient.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooth interactions
    initializeToothInteractions();
});

function initializeToothInteractions() {
    const toothContainers = document.querySelectorAll('.tooth-container');
    const toothInfoPanel = document.getElementById('tooth-info-panel');
    const tooltip = document.getElementById('tooth-tooltip');

    toothContainers.forEach(container => {
        container.addEventListener('click', function() {
            const toothNumber = this.dataset.tooth;
            const toothName = this.dataset.name;
            const status = this.dataset.status;
            const notes = this.dataset.notes;

            // Update tooth info panel
            document.getElementById('tooth-number').textContent = toothNumber;
            document.getElementById('tooth-name').textContent = toothName;
            document.getElementById('tooth-notes').textContent = notes || 'No notes available';
            
            // Update status badge
            const statusElement = document.getElementById('tooth-status');
            statusElement.textContent = status || 'No data';
            statusElement.className = 'ml-2 px-2 py-1 rounded-full text-xs font-medium';
            
            if (status) {
                switch (status.toLowerCase()) {
                    case 'healthy':
                    case 'good':
                        statusElement.className += ' bg-green-100 text-green-800';
                        break;
                    case 'watch':
                    case 'monitoring':
                        statusElement.className += ' bg-yellow-100 text-yellow-800';
                        break;
                    case 'treatment needed':
                    case 'cavity':
                    case 'issue':
                        statusElement.className += ' bg-red-100 text-red-800';
                        break;
                    default:
                        statusElement.className += ' bg-blue-100 text-blue-800';
                }
            } else {
                statusElement.className += ' bg-gray-100 text-gray-800';
            }
            
            // Show panel
            toothInfoPanel.classList.remove('hidden');
            toothInfoPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });

        // Tooltip functionality
        container.addEventListener('mouseenter', function(e) {
            const toothNumber = this.dataset.tooth;
            const toothName = this.dataset.name;
            const status = this.dataset.status || 'No data';
            
            document.getElementById('tooltip-content').innerHTML = `
                <div class="font-medium">Tooth #${toothNumber}</div>
                <div class="text-xs">${toothName}</div>
                <div class="text-xs">Status: ${status}</div>
            `;
            
            tooltip.classList.remove('hidden');
            updateTooltipPosition(e, tooltip);
        });

        container.addEventListener('mouseleave', function() {
            tooltip.classList.add('hidden');
        });

        container.addEventListener('mousemove', function(e) {
            updateTooltipPosition(e, tooltip);
        });
    });
}

function updateTooltipPosition(e, tooltip) {
    const x = e.clientX + 10;
    const y = e.clientY - 10;
    
    tooltip.style.left = x + 'px';
    tooltip.style.top = y + 'px';
}

// Treatment Plan Modal Functions
function viewTreatmentPlan(treatmentPlanId) {
    const modal = document.getElementById('treatmentPlanModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Load treatment plan details
    fetch(`<?php echo BASE_URL; ?>/patient/get-treatment-plan-details?treatment_plan_id=${treatmentPlanId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTreatmentPlanDetails(data.treatmentPlan);
            } else {
                document.getElementById('treatmentPlanContent').innerHTML = `
                    <div class="text-center py-8">
                        <div class="text-red-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600">${data.message || 'Failed to load treatment plan details'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading treatment plan:', error);
            document.getElementById('treatmentPlanContent').innerHTML = `
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

function closeTreatmentPlanModal() {
    const modal = document.getElementById('treatmentPlanModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function displayTreatmentPlanDetails(plan) {
    const content = document.getElementById('treatmentPlanContent');
    
    content.innerHTML = `
        <div class="space-y-6">
            <!-- Treatment Plan Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="glass-card bg-white rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-nhd-blue mb-4">Treatment Plan Overview</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Plan ID:</span>
                            <span class="font-medium">#${String(plan.TreatmentPlanID).padStart(4, '0')}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${getStatusClass(plan.Status)}">
                                ${plan.Status.charAt(0).toUpperCase() + plan.Status.slice(1)}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Assigned:</span>
                            <span class="font-medium">${formatDate(plan.AssignedAt)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Doctor:</span>
                            <span class="font-medium">Dr. ${plan.DoctorName}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Progress:</span>
                            <span class="font-medium">${plan.progress}%</span>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-nhd-blue h-3 rounded-full transition-all duration-300" 
                                 style="width: ${plan.progress}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="glass-card bg-white rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-nhd-blue mb-4">Appointment Details</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Appointment ID:</span>
                            <span class="font-medium">#${String(plan.AppointmentID).padStart(6, '0')}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium">${formatDate(plan.AppointmentDate)}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium">${plan.AppointmentType}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Specialization:</span>
                            <span class="font-medium">${plan.Specialization}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Doctor's Notes -->
            ${plan.DentistNotes ? `
                <div class="glass-card bg-white rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-nhd-blue mb-4">Doctor's Notes</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">${plan.DentistNotes}</p>
                    </div>
                </div>
            ` : ''}
            
            <!-- Treatment Items -->
            <div class="glass-card bg-white rounded-xl p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-nhd-blue mb-4">Treatment Items</h4>
                ${plan.items && plan.items.length > 0 ? `
                    <div class="space-y-4">
                        ${plan.items.map(item => `
                            <div class="border border-gray-200 rounded-lg p-4 ${item.CompletedAt ? 'bg-green-50' : 'bg-white'}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <div class="w-3 h-3 rounded-full ${item.CompletedAt ? 'bg-green-500' : 'bg-gray-300'}"></div>
                                            <h5 class="font-medium text-gray-900 ${item.CompletedAt ? 'line-through' : ''}">
                                                ${item.ToothNumber ? `Tooth #${item.ToothNumber} - ` : ''}${item.Description}
                                            </h5>
                                            ${item.IsChargedToAccount == '1' ? `
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                    Charged to Account
                                                </span>
                                            ` : ''}
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Procedure Code:</span>
                                                <div>${item.ProcedureCode || 'N/A'}</div>
                                            </div>
                                            <div>
                                                <span class="font-medium">Cost:</span>
                                                <div class="text-nhd-brown font-semibold">₱${parseFloat(item.Cost || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                                            </div>
                                            <div>
                                                <span class="font-medium">Scheduled:</span>
                                                <div>${item.ScheduledDate ? formatDate(item.ScheduledDate) : 'Not scheduled'}</div>
                                            </div>
                                        </div>
                                        
                                        ${item.CompletedAt ? `
                                            <div class="mt-2 text-sm text-green-600">
                                                ✓ Completed on ${formatDate(item.CompletedAt)}
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-700">Total Treatment Cost:</span>
                            <span class="text-xl font-bold text-nhd-brown">₱${calculateTotalCost(plan.items).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-gray-600">Completed Cost:</span>
                            <span class="text-sm font-medium text-green-600">₱${calculateCompletedCost(plan.items).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                        </div>
                    </div>
                ` : `
                    <p class="text-gray-500 text-center py-8">No treatment items found</p>
                `}
            </div>
        </div>
    `;
}

function getStatusClass(status) {
    switch(status) {
        case 'completed': return 'bg-green-100 text-green-800';
        case 'in_progress': return 'bg-blue-100 text-blue-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function calculateTotalCost(items) {
    return items.reduce((total, item) => total + parseFloat(item.Cost || 0), 0);
}

function calculateCompletedCost(items) {
    return items
        .filter(item => item.CompletedAt)
        .reduce((total, item) => total + parseFloat(item.Cost || 0), 0);
}
</script>

<style>
.tooth-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.tooth {
    transition: all 0.2s ease-in-out;
}

.tooth:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.grid-cols-16 {
    grid-template-columns: repeat(16, minmax(0, 1fr));
}

#tooth-tooltip {
    z-index: 9999;
}
</style>