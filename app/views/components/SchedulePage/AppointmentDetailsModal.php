<div id="appointmentDetailsModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="glass-card bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-nhd-brown">Appointment Details & Report</h3>
            <button onclick="closeAppointmentDetailsModal()" class="glass-card bg-nhd-blue/80 text-2xl font-bold">
                &times;
            </button>
        </div>
        
        <div class="p-6">
            <div id="modalLoading" class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-nhd-blue mx-auto"></div>
                <p class="text-gray-600 mt-2">Loading appointment details...</p>
            </div>
            
            <div id="modalContent" class="hidden">
                <div class="glass-card bg-gray-50/50 border-gray-200 shadow-sm border-2 rounded-2xl p-6 mb-6">
                    <h4 class="text-lg font-semibold text-nhd-blue mb-4">Appointment Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Patient:</span>
                            <p id="modalPatientName" class="text-gray-900 font-semibold"></p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Date & Time:</span>
                            <p id="modalDateTime" class="text-gray-900"></p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Type:</span>
                            <p id="modalType" class="text-gray-900"></p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Appointment ID:</span>
                            <p id="modalAppointmentId" class="text-gray-900"></p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="font-medium text-gray-700">Reason for Visit:</span>
                            <p id="modalReason" class="text-gray-900 mt-1"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Medical Report Form -->
                <div class="glass-card bg-white/50 border-gray-200 shadow-sm border-2 rounded-2xl p-6">
                    <h4 class="text-lg font-semibold text-nhd-blue mb-4">Medical Report</h4>
                    <form id="appointmentReportForm">
                        <input type="hidden" id="reportAppointmentId" name="appointmentId">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Vital Signs -->
                            <div class="space-y-4">
                                <h5 class="text-base font-medium text-gray-800 border-b border-gray-200 pb-2">Vital Signs</h5>
                                
                                <div>
                                    <label for="bloodPressure" class="block text-sm font-medium text-gray-700 mb-1">Blood Pressure</label>
                                    <input type="text" 
                                           id="bloodPressure" 
                                           name="bloodPressure" 
                                           placeholder="e.g., 120/80 mmHg"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label for="pulseRate" class="block text-sm font-medium text-gray-700 mb-1">Pulse Rate (BPM)</label>
                                    <input type="number" 
                                           id="pulseRate" 
                                           name="pulseRate" 
                                           placeholder="e.g., 72"
                                           min="30" 
                                           max="200"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label for="temperature" class="block text-sm font-medium text-gray-700 mb-1">Temperature (°F)</label>
                                    <input type="number" 
                                           id="temperature" 
                                           name="temperature" 
                                           placeholder="e.g., 98.6"
                                           step="0.1"
                                           min="90" 
                                           max="110"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label for="respiratoryRate" class="block text-sm font-medium text-gray-700 mb-1">Respiratory Rate (breaths/min)</label>
                                    <input type="number" 
                                           id="respiratoryRate" 
                                           name="respiratoryRate" 
                                           placeholder="e.g., 16"
                                           min="5" 
                                           max="50"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                                </div>
                            </div>
                            
                            <!-- General Assessment -->
                            <div class="space-y-4">
                                <h5 class="text-base font-medium text-gray-800 border-b border-gray-200 pb-2">General Assessment</h5>
                                
                                <div>
                                    <label for="generalAppearance" class="block text-sm font-medium text-gray-700 mb-1">General Appearance & Notes</label>
                                    <textarea id="generalAppearance" 
                                              name="generalAppearance" 
                                              rows="6"
                                              placeholder="Patient appearance, behavior, complaints, diagnosis, treatment plan, medications prescribed, follow-up instructions, etc."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent resize-vertical"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                            <button type="button" 
                                    onclick="closeAppointmentDetailsModal()"
                                    class="px-4 py-2 text-gray-600 glass-card bg-gray-100/85 rounded-2xl hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2 glass-card bg-nhd-brown/85 text-white font-semibold rounded-2xl hover:bg-opacity-90 transition-colors focus:outline-none focus:ring-2 focus:ring-nhd-brown focus:ring-offset-2">
                                Save Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div id="modalError" class="hidden text-center py-8">
                <div class="text-red-500 mb-2">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p id="modalErrorMessage" class="text-gray-600"></p>
            </div>
        </div>
    </div>
</div>