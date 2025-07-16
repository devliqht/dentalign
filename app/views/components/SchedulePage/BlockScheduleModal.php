<div id="blockScheduleModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="glass-card bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-bold text-nhd-brown">Manage Availability</h3>
            <button onclick="closeBlockScheduleModal()" class="inline-flex items-center px-4 py-2 text-sm bg-nhd-blue/80 glass-card text-white rounded-2xl hover:bg-nhd-blue transition-colors">×</button>
        </div>
        
        <form id="blockScheduleForm" onsubmit="submitBlockedSlots(event)">
            <div class="p-6">
                <p class="text-gray-700 mb-4">
                    Select time slots to block for: 
                    <strong id="block-modal-date-display" class="text-nhd-brown"></strong>
                </p>

                <!-- Time slots will be dynamically inserted here -->
                <div id="time-slot-checkboxes" class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="text-center col-span-full py-4">Loading slots...</div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 px-6 py-4 bg-gray-50/50 rounded-b-2xl border-t">
                <button type="button" onclick="closeBlockScheduleModal()" class="px-4 py-2 text-gray-600 glass-card bg-gray-100/85 rounded-2xl hover:bg-gray-200">
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm bg-nhd-blue/80 glass-card text-white rounded-2xl hover:bg-nhd-blue transition-colors">
                    Save Blocked Slots
                </button>
            </div>
        </form>
    </div>
</div>