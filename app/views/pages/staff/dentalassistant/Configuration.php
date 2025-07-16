<?php
require_once __DIR__ . "/../../../components/SortableAppointmentTable.php";
?>

<div class="px-4 pb-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
                    Configuration
                </h1>
                <p class="text-gray-600">Manage system settings, service prices, and payment configurations</p>
            </div>
            <div class="flex space-x-3">
                <button id="refreshDataBtn" class="glass-card bg-nhd-blue text-white px-4 py-2 rounded-xl shadow-sm hover:bg-nhd-blue/90 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Service Price Management -->
    <div class="glass-card rounded-2xl border-gray-200 border-1 shadow-sm mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-green-700">Service Price Management</h3>
            <button id="addServiceBtn" class="glass-card bg-green-600 text-white px-4 py-2 rounded-xl shadow-sm hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>Add Service
            </button>
        </div>
        
        <div id="servicesTableContainer" class="overflow-x-auto">
            <table class="w-full" id="servicesTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="servicesTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Services will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Overdue Configuration -->
    <div class="glass-card bg-blue-50/50 rounded-2xl border-blue-200 border-1 shadow-sm mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-nhd-blue">Overdue Payment Configuration</h3>
            <button id="editConfigBtn" class="glass-card bg-nhd-blue text-white px-4 py-2 rounded-xl shadow-sm hover:bg-nhd-blue/90 transition-colors">
                <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>Edit Configuration
            </button>
        </div>
        
        <div id="configDisplay" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="glass-card bg-white/60 p-4 rounded-xl shadow-none border-1 border-gray-200">
                <p class="text-sm text-gray-600">Overdue Fee Percentage</p>
                <p class="text-2xl font-bold text-nhd-blue" id="currentPercentage">5.00%</p>
            </div>
            <div class="glass-card bg-white/60 p-4 rounded-xl shadow-none border-1 border-gray-200">
                <p class="text-sm text-gray-600">Grace Period</p>
                <p class="text-2xl font-bold text-nhd-blue" id="currentGracePeriod">0 days</p>
            </div>
            <div class="glass-card bg-white/60 p-4 rounded-xl shadow-none border-1 border-gray-200">
                <p class="text-sm text-gray-600">Last Updated</p>
                <p class="text-sm font-medium text-gray-700" id="lastUpdated">Never</p>
            </div>
        </div>
        
        <div id="configForm" class="hidden mt-4 p-4 bg-white/60 rounded-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overdue Fee Percentage (%)</label>
                    <input type="number" id="overduePercentage" step="0.01" min="0" max="100" class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Grace Period (Days)</label>
                    <input type="number" id="gracePeriodDays" min="0" max="365" class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Configuration Name</label>
                <input type="text" id="configName" placeholder="Enter configuration name..." class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20">
            </div>
            <div class="flex space-x-3">
                <button id="saveConfigBtn" class="glass-card bg-green-500 text-white px-4 py-2 rounded-xl shadow-sm hover:bg-green-600 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>Save Configuration
                </button>
                <button id="cancelConfigBtn" class="glass-card bg-gray-500 text-white px-4 py-2 rounded-xl shadow-sm hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Service Price Management Modal -->
<div id="servicePriceModal" class="fixed inset-0 bg-gray-600/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-nhd-brown" id="servicePriceModalTitle">Add Service Price</h3>
            <button id="closeServicePriceModal" class="glass-card bg-nhd-blue/80 text-white text-sm transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <div class="p-5">
            <form id="servicePriceForm">
                <input type="hidden" id="servicePriceID" value="">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Service Name</label>
                    <input type="text" id="serviceName" class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20" placeholder="Enter service name">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (₱)</label>
                    <input type="number" id="servicePrice" step="0.01" min="0" class="w-full p-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-nhd-blue/20" placeholder="0.00">
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="isActive" class="mr-2 rounded" checked>
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelServicePriceBtn" class="glass-card px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="saveServicePriceBtn" class="glass-card px-4 py-2 bg-nhd-blue/80 text-white rounded-xl hover:bg-nhd-blue/90 transition-colors">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
window.BASE_URL = '<?php echo BASE_URL; ?>';

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
.glass-card {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    table {
        font-size: 0.875rem;
    }
}
</style>

<script src="<?php echo BASE_URL; ?>/app/views/scripts/PaymentManagement/ServicePriceManagement.js"></script>
