<div class="px-4 pb-8">
    <div class="mb-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
            Patient Records
        </h2>
        <p class="text-gray-600 mb-2">
            Complete patient database with medical records
        </p>
        <div class="flex items-center space-x-4 text-sm text-gray-500">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-2.239"/>
                </svg>
                Total Patients: <?php echo count($patients); ?>
            </span>
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Records Available: <?php echo array_reduce(
                    $patients,
                    function ($count, $patient) {
                        return $count + ($patient["RecordID"] ? 1 : 0);
                    },
                    0
                ); ?>
            </span>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-6 glass-card rounded-2xl p-4">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" id="patient-search" placeholder="Search patients by name, email..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex gap-2">
                <select id="record-filter" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                    <option value="all">All Patients</option>
                    <option value="with-records">With Records</option>
                    <option value="without-records">Without Records</option>
                </select>
                <button id="export-btn" class="glass-card bg-nhd-blue text-white px-4 py-3 rounded-xl hover:bg-nhd-blue/90 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Patients Grid -->
    <?php include "app/views/components/PatientRecords/PatientsGrid.php"; ?>


    <!-- Empty State -->
    <div id="empty-state" class="hidden text-center py-12">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-2.239"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No patients found</h3>
        <p class="text-gray-500">Try adjusting your search criteria or filters.</p>
    </div>
</div>

<!-- Patient Detail Modal -->
<div id="patient-detail-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="glass-card bg-gray-100/85 rounded-2xl shadow-xl w-full max-w-6xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-nhd-brown">Patient Details</h2>
                <button onclick="closePatientDetail()" class="text-nhd-pale glass-card bg-nhd-blue/80">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="patient-detail-content">
                <!-- Content will be loaded dynamically -->
                <div class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-nhd-blue"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Record Modal -->
<div id="edit-record-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-nhd-brown">Edit Patient Record</h2>
                <button onclick="closeEditRecord()" class="text-nhd-pale glass-card bg-nhd-blue/80">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="edit-record-form" class="space-y-6">
                <input type="hidden" id="edit-record-id" name="recordId">
                <input type="hidden" id="edit-patient-id" name="patientId">
                
                <div id="edit-patient-info" class="bg-gray-50 rounded-xl p-4 mb-6">
                    <!-- Patient info will be loaded here -->
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="edit-height" class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                        <input type="number" id="edit-height" name="height" step="0.1" min="0" max="300"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                    </div>
                    <div>
                        <label for="edit-weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                        <input type="number" id="edit-weight" name="weight" step="0.1" min="0" max="500"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="edit-allergies" class="block text-sm font-medium text-gray-700 mb-2">Allergies</label>
                    <textarea id="edit-allergies" name="allergies" rows="3" placeholder="List any known allergies..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-nhd-blue focus:border-transparent"></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 px-6 py-3 text-nhd-pale glass-card bg-nhd-blue/80 transition-colors">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditRecord()" class="px-6 py-3 border text-nhd-pale glass-card bg-nhd-blue/80 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentPatientData = null;

document.getElementById('patient-search').addEventListener('input', function() {
    filterPatients();
});

document.getElementById('record-filter').addEventListener('change', function() {
    filterPatients();
});


</script>
