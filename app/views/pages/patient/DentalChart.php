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

    <!-- Treatment History -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mt-6">
        <h3 class="text-xl font-semibold text-nhd-blue mb-4">Recent Treatment History</h3>
        <div class="space-y-4">
            <div class="text-gray-500 text-center py-8">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p>Treatment history will be updated by your dentist after appointments.</p>
            </div>
        </div>
    </div>
</div>

<!-- Hover Tooltip -->
<div id="tooth-tooltip" class="fixed z-50 bg-gray-900 text-white text-sm rounded-lg px-3 py-2 pointer-events-none opacity-0 transition-opacity duration-200">
    <div id="tooltip-content"></div>
    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toothContainers = document.querySelectorAll('.tooth-container');
    const toothInfoPanel = document.getElementById('tooth-info-panel');
    const tooltip = document.getElementById('tooth-tooltip');
    const tooltipContent = document.getElementById('tooltip-content');
    
    toothContainers.forEach(container => {
        container.addEventListener('mouseenter', function(e) {
            const toothNumber = this.dataset.tooth;
            const toothName = this.dataset.name;
            const status = this.dataset.status || 'No status';
            
            tooltipContent.innerHTML = `
                <div><strong>Tooth #${toothNumber}</strong></div>
                <div class="text-xs mt-1">${toothName}</div>
                <div class="text-xs mt-1">Status: ${status}</div>
            `;
            
            tooltip.style.opacity = '1';
            positionTooltip(e);
        });
        
        container.addEventListener('mouseleave', function() {
            tooltip.style.opacity = '0';
        });
        
        container.addEventListener('mousemove', positionTooltip);
        
        container.addEventListener('click', function() {
            const toothNumber = this.dataset.tooth;
            const toothName = this.dataset.name;
            const status = this.dataset.status || 'No status recorded';
            const notes = this.dataset.notes || 'No notes available';
            
            document.getElementById('tooth-number').textContent = toothNumber;
            document.getElementById('tooth-name').textContent = toothName;
            document.getElementById('tooth-notes').textContent = notes;
            
            const statusElement = document.getElementById('tooth-status');
            statusElement.textContent = status;
            
            // Set status color
            statusElement.className = 'ml-2 px-2 py-1 rounded-full text-xs font-medium ';
            if (status.toLowerCase().includes('healthy') || status.toLowerCase().includes('good')) {
                statusElement.className += 'bg-green-100 text-green-800';
            } else if (status.toLowerCase().includes('watch') || status.toLowerCase().includes('monitoring')) {
                statusElement.className += 'bg-yellow-100 text-yellow-800';
            } else if (status.toLowerCase().includes('treatment') || status.toLowerCase().includes('cavity')) {
                statusElement.className += 'bg-red-100 text-red-800';
            } else if (status === 'No status recorded') {
                statusElement.className += 'bg-gray-100 text-gray-800';
            } else {
                statusElement.className += 'bg-blue-100 text-blue-800';
            }
            
            toothInfoPanel.classList.remove('hidden');
            toothInfoPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });
    
    function positionTooltip(e) {
        const rect = tooltip.getBoundingClientRect();
        tooltip.style.left = (e.pageX - rect.width / 2) + 'px';
        tooltip.style.top = (e.pageY - rect.height - 10) + 'px';
    }
});
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