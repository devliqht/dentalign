<div class="px-4 pb-8">
    <div class="pb-6">
        <div class="flex items-center mb-4">
            <button onclick="window.history.back()" class="glass-card bg-gray-200/80 text-gray-700 px-3 py-2 rounded-full text-sm hover:bg-gray-300/80 transition-colors mr-4">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </button>
            <div>
                <h2 class="text-4xl font-bold text-nhd-brown font-family-bodoni tracking-tight">Edit Dental Chart</h2>
                <p class="text-gray-600">Comprehensive dental chart management for <span id="patient-name" class="font-semibold"></span></p>
            </div>
        </div>

        <!-- Patient Info Card -->
        <div class="bg-white rounded-2xl border-1 shadow-sm border-gray-200 p-4 mb-6">
            <div id="patient-info" class="flex items-center justify-between">
                <div>
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-32 mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-48"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    Patient ID: #<span id="patient-id-display"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dental Chart Container -->
    <div class="bg-white rounded-2xl mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-semibold text-nhd-blue">Interactive Dental Chart</h3>
                <p class="text-gray-600 text-sm">Click on any tooth to edit its status and notes</p>
            </div>
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
                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                    <span>Other</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-200 rounded-full mr-2"></div>
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
                        <div class="tooth-container relative group cursor-pointer"
                             data-tooth="<?php echo $i; ?>"
                             data-name="<?php echo htmlspecialchars(
                                 \DentalChartItem::getToothName($i)
                             ); ?>">
                            <div class="tooth bg-gray-200 w-8 h-12 rounded-t-full border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-end justify-center">
                                <span class="text-xs font-bold text-gray-700 mb-1"><?php echo $i; ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>

                    <!-- Upper Left Quadrant (9-16) -->
                    <?php for ($i = 9; $i <= 16; $i++): ?>
                        <div class="tooth-container relative group cursor-pointer"
                             data-tooth="<?php echo $i; ?>"
                             data-name="<?php echo htmlspecialchars(
                                 \DentalChartItem::getToothName($i)
                             ); ?>">
                            <div class="tooth bg-gray-200 w-8 h-12 rounded-t-full border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-end justify-center">
                                <span class="text-xs font-bold text-gray-700 mb-1"><?php echo $i; ?></span>
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
                        <div class="tooth-container relative group cursor-pointer"
                             data-tooth="<?php echo $i; ?>"
                             data-name="<?php echo htmlspecialchars(
                                 \DentalChartItem::getToothName($i)
                             ); ?>">
                            <div class="tooth bg-gray-200 w-8 h-12 rounded-b-full border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-start justify-center">
                                <span class="text-xs font-bold text-gray-700 mt-1"><?php echo $i; ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>

                    <!-- Lower Left Quadrant (17-24) -->
                    <?php for ($i = 24; $i >= 17; $i--): ?>
                        <div class="tooth-container relative group cursor-pointer"
                             data-tooth="<?php echo $i; ?>"
                             data-name="<?php echo htmlspecialchars(
                                 \DentalChartItem::getToothName($i)
                             ); ?>">
                            <div class="tooth bg-gray-200 w-8 h-12 rounded-b-full border-2 border-gray-300 hover:shadow-lg transition-all duration-200 transform hover:scale-110 flex items-start justify-center">
                                <span class="text-xs font-bold text-gray-700 mt-1"><?php echo $i; ?></span>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-nhd-brown/50 p-6 my-6">
        <h3 class="text-xl font-semibold text-nhd-brown mb-4">Quick Actions</h3>
        <div class="flex flex-row gap-4">
            <button onclick="saveAllChanges()" class="glass-card bg-nhd-blue/80 text-white px-3 py-2 rounded-2xl hover:bg-nhd-blue transition-colors w-fit shadow-sm text-sm">
                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Save All Changes
            </button>
            <button onclick="markAllTeethAs('Healthy')" class="glass-card bg-green-600/80 px-3 py-2 rounded-2xl hover:bg-green-600 transition-colors w-fit shadow-sm text-sm">
                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mark All Healthy
            </button>
            <button onclick="generateReport()" class="glass-card bg-gray-100/80 text-gray-800 px-3 py-2 rounded-2xl hover:bg-gray-200/80 border-gray-200 border-1 transition-colors w-fit shadow-sm text-sm">
                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Generate Report
            </button>
            <button onclick="clearAllNotes()" class="glass-card bg-gray-100/80 text-gray-800 px-3 py-2 rounded-2xl hover:bg-gray-200/80 border-gray-200 border-1 transition-colors w-fit shadow-sm text-sm">
                <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Clear All Notes
            </button>
        </div>
    </div>

    <!-- Treatment Plan Management -->
    <div class="bg-white rounded-2xl shadow-sm border border-nhd-brown/50 p-6 my-6">
        <h3 class="text-xl font-semibold text-nhd-brown mb-4">Treatment Plan Management</h3>

        <!-- Existing Treatment Plans -->
        <div id="existing-treatment-plans" class="mb-6">
            <h4 class="text-lg font-medium text-gray-700 mb-3">Existing Treatment Plans</h4>
            <div id="treatment-plans-list" class="space-y-3">
                <div class="text-gray-500 text-center py-4 text-sm">
                    Loading treatment plans...
                </div>
            </div>
        </div>

        <!-- Create New Treatment Plan -->
        <div class="border-t border-gray-200 pt-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-gray-700">Create New Treatment Plan</h4>
                <button id="create-treatment-plan-btn" class="glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl hover:bg-nhd-blue transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Treatment Plan
                </button>
            </div>

            <!-- Treatment Plan Creation Form -->
            <div id="treatment-plan-form" class="hidden space-y-4">
                <div>
                    <label for="appointment-report-select" class="block text-sm font-medium text-gray-700 mb-2">
                        Based on Appointment Report
                    </label>
                    <select id="appointment-report-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                        <option value="">Select an appointment report...</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Only completed appointments with reports are available</p>
                </div>

                <div>
                    <label for="treatment-plan-notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Dentist Notes
                    </label>
                    <textarea id="treatment-plan-notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent resize-vertical"
                              placeholder="Enter treatment plan notes and recommendations..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Treatment Status</label>
                    <select id="treatment-plan-status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- Treatment Items Section -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <h5 class="text-md font-medium text-gray-700">Treatment Items</h5>
                        <button type="button" id="add-treatment-item-btn" class="glass-card bg-green-300/80 text-green-800 px-3 py-1 hover:bg-green-200/80 transition-colors text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <div id="treatment-items-container" class="space-y-3">
                        <!-- Treatment items will be added dynamically -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" id="save-treatment-plan-btn" class="flex-1 bg-nhd-blue text-white px-4 py-2 rounded-lg hover:bg-nhd-blue/90 transition-colors">
                        Create Treatment Plan
                    </button>
                    <button type="button" id="cancel-treatment-plan-btn" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tooth Edit Modal -->
<div id="tooth-edit-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="glass-card bg-white/90 rounded-2xl shadow-xl w-full max-w-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-nhd-brown">Edit Tooth</h2>
                    <p class="text-gray-600">
                        Tooth #<span id="edit-tooth-number"></span> - <span id="edit-tooth-name"></span>
                    </p>
                </div>
                <button onclick="closeToothEditModal()" class="glass-card bg-nhd-blue/80 text-white shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="tooth-edit-form" class="space-y-6">
                <input type="hidden" id="edit-tooth-number-input" name="toothNumber">

                <!-- Status Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Tooth Status</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="Healthy" class="sr-only" onchange="updateStatusPreview(this.value)">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                            <span class="font-medium">Healthy</span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="Watch" class="sr-only" onchange="updateStatusPreview(this.value)">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="font-medium">Watch</span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="Treatment Needed" class="sr-only" onchange="updateStatusPreview(this.value)">
                            <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                            <span class="font-medium">Treatment Needed</span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="" class="sr-only" onchange="updateStatusPreview('')">
                            <div class="w-4 h-4 bg-gray-200 rounded-full mr-3"></div>
                            <span class="font-medium">No Status</span>
                        </label>
                    </div>
                </div>

                <!-- Common Conditions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Common Conditions (Click to add)</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="addCondition('Cavity')" class="px-3 py-1 text-black glass-card bg-gray-200/80 shadow-sm rounded-full text-sm hover:bg-red-200 transition-colors">+ Cavity</button>
                        <button type="button" onclick="addCondition('Filling')" class="px-3 py-1 text-black glass-card bg-gray-200/80 shadow-sm rounded-full text-sm hover:bg-blue-200 transition-colors">+ Filling</button>
                        <button type="button" onclick="addCondition('Crown')" class="px-3 py-1 text-black glass-card bg-gray-200/80 shadow-sm rounded-full text-sm hover:bg-purple-200 transition-colors">+ Crown</button>
                        <button type="button" onclick="addCondition('Root Canal')" class="px-3 py-1 text-black glass-card bg-gray-200/80 shadow-sm rounded-full text-sm hover:bg-orange-200 transition-colors">+ Root Canal</button>
                        <button type="button" onclick="addCondition('Extraction')" class="px-3 py-1 text-black glass-card bg-gray-200/80 shadow-sm rounded-full text-sm hover:bg-gray-200 transition-colors">+ Extraction</button>
                        <button type="button" onclick="addCondition('Plaque')" class="px-3 py-1 text-black glass-card bg-gray-200/80 shadow-sm rounded-full text-sm hover:bg-yellow-200 transition-colors">+ Plaque</button>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="edit-tooth-notes" class="block text-sm font-medium text-gray-700 mb-2">Clinical Notes</label>
                    <textarea id="edit-tooth-notes" name="notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent resize-vertical"
                              placeholder="Enter detailed clinical observations, treatment plans, or recommendations..."></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t">
                    <button type="button" onclick="saveToothData()"
                            class="flex-1 bg-nhd-blue/80 glass-card text-white px-4 py-2 rounded-2xl hover:bg-nhd-blue/90 transition-colors">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeToothEditModal()"
                            class="px-4 py-2 border glass-card bg-red-600/80 hover:bg-red-600 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Treatment Plan Edit Modal -->
<div id="treatment-plan-edit-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="glass-card bg-white/95 rounded-2xl shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                <div>
                    <h2 class="text-2xl font-bold text-nhd-brown">Treatment Plan Details</h2>
                    <p class="text-gray-600" id="treatment-plan-subtitle">
                        Treatment Plan #<span id="treatment-plan-id-display"></span> - <span id="treatment-plan-patient-name"></span>
                    </p>
                </div>
                <button onclick="closeTreatmentPlanModal()" class="glass-card bg-gray-200/80 text-gray-700 px-3 py-2 rounded-full hover:bg-gray-300/80 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
        </div>

        <!-- Modal Content -->
        <div class="flex-1 overflow-y-auto p-6">
                <!-- Treatment Plan Overview -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="treatment-plan-status-edit" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Progress</label>
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3">
                                    <div id="treatment-plan-progress-bar" class="bg-nhd-blue h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <span id="treatment-plan-progress-text" class="text-sm font-medium text-gray-700">0%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                            <p id="treatment-plan-created-date" class="text-gray-600 text-sm py-2"></p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dentist Notes</label>
                        <textarea id="treatment-plan-notes-edit" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent resize-vertical"
                                  placeholder="Enter treatment plan notes and recommendations..."></textarea>
                    </div>
                </div>

                <!-- Treatment Items Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-nhd-brown">Treatment Items</h3>
                        <button id="add-treatment-item-modal-btn" class="glass-card bg-green-600/80 px-4 py-2 hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <div id="treatment-items-list" class="space-y-4">
                        <!-- Treatment items will be populated here -->
                    </div>
                </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex gap-3 p-6 border-t border-gray-200 bg-gray-50 items-end justify-end w-full flex-shrink-0">
                <button id="save-treatment-plan-changes-btn" class="flex 1 glass-card bg-nhd-blue/80 shadow-md px-4 py-2 hover:bg-nhd-blue/90 transition-colors">
                    Save All Changes
                </button>
                <button onclick="closeTreatmentPlanModal()" class="px-4 py-2 glass-card bg-gray-800/80 hover:bg-nhd-blue shadow-md transition-colors">
                    Cancel
                </button>
                <button id="delete-treatment-plan-btn" class="px-4 py-2 glass-card bg-red-600/80 text-white hover:bg-red-700 transition-colors">
                    Delete Plan
                </button>
        </div>
    </div>
</div>

<!-- Treatment Item Edit Modal -->
<div id="treatment-item-edit-modal" class="hidden fixed inset-0 bg-black/50 z-60 flex items-center justify-center p-4">
    <div class="glass-card bg-white/95 rounded-2xl shadow-xl w-full max-w-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-nhd-brown">Edit Treatment Item</h2>
                    <p class="text-gray-600" id="treatment-item-subtitle">Treatment Item Details</p>
                </div>
                <button onclick="closeTreatmentItemModal()" class="glass-card bg-gray-200/80 text-gray-700 px-3 py-2 rounded-full hover:bg-gray-300/80 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="treatment-item-edit-form" class="space-y-4">
                <input type="hidden" id="edit-treatment-item-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tooth Number (Optional)</label>
                        <select id="edit-item-tooth-number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                            <option value="">No specific tooth</option>
                            <!-- Tooth options will be populated by JavaScript -->
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Procedure Code</label>
                        <select id="edit-item-procedure-code" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                            <option value="">Select procedure...</option>
                            <!-- Procedure options will be populated by JavaScript -->
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <input type="text" id="edit-item-description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent"
                               placeholder="Enter treatment description...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost (₱)</label>
                        <input type="number" step="0.01" min="0" id="edit-item-cost" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent"
                               placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date (Optional)</label>
                        <input type="date" id="edit-item-scheduled-date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="edit-item-completed" class="mr-2 rounded border-gray-300 text-nhd-blue focus:ring-nhd-blue">
                            <label for="edit-item-completed" class="text-sm text-gray-700">Mark as completed</label>
                        </div>
                        <div id="edit-item-completed-date-container" class="mt-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Completion Date & Time</label>
                            <input type="datetime-local" id="edit-item-completed-date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="saveTreatmentItem()" class="flex-1 glass-card bg-nhd-blue/80 px-4 py-2  hover:bg-nhd-blue transition-colors">
                        Save Item
                    </button>
                    <button type="button" onclick="closeTreatmentItemModal()" class="px-4 py-2 border glass-card bg-gray-600/80 hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="button" onclick="deleteTreatmentItem()" class="px-4 py-2 glass-card bg-red-600/80 hover:bg-red-600 transition-colors">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once "app/views/components/Toast.php"; ?>

<script>
window.BASE_URL = '<?php echo BASE_URL; ?>';
</script>
<script src="<?php echo BASE_URL; ?>/app/views/scripts/Toast.js"></script>
<script src="<?php echo BASE_URL; ?>/app/views/scripts/DentalChart/DentalChartEdit.js"></script>

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

label:has(input[type="radio"]:checked) {
    background-color: #b8cce6;
}
</style>
