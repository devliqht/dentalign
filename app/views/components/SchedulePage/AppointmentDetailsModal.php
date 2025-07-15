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
                            <span class="font-medium text-gray-700">Doctor:</span>
                            <p id="modalDoctor" class="text-gray-900 mt-1 font-semibold"></p>
                            <div id="doctorChangeSection" class="mt-2 hidden">
                                <label for="modalDoctorSelect" class="block text-xs font-medium text-gray-600 mb-1">Change Doctor:</label>
                                <div class="flex items-center space-x-2">
                                    <select id="modalDoctorSelect" class="px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-nhd-blue focus:border-transparent">
                                        <option value="">Select a doctor...</option>
                                    </select>
                                    <button type="button" 
                                            id="updateDoctorBtn" 
                                            class="px-3 py-1 text-xs bg-nhd-blue text-white rounded hover:bg-opacity-80 transition-colors">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Type:</span>
                            <p id="modalType" class="text-gray-900"></p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Appointment ID:</span>
                            <p id="modalAppointmentId" class="text-gray-900"></p>
                        </div>
                        <div>
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
                                <h5 class="text-base font-medium text-gray-800 border-b border-gray-200 pb-2">Appointment Notes</h5>
                                
                                <div>
                                    <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">Diagnosis</label>
                                    <input rows="6" 
                                           id="diagnosis" 
                                           name="diagnosis" 
                                           placeholder="Diagnosis here..."
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label for="xrayImages" class="block text-sm font-medium text-gray-700 mb-1">X-Ray Images</label>
                                    <input rows="1" 
                                           id="xrayImages" 
                                           name="xrayImages" 
                                           placeholder="Link to file..."
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                                </div>
                                
                            </div>
                            
                            <div class="space-y-4">
                                <h5 class="text-base font-medium text-gray-800 border-b border-gray-200 pb-2">Notes</h5>
                                
                                <div>
                                    <label for="oralNotes" class="block text-sm font-medium text-gray-700 mb-1">General Appearance & Notes</label>
                                    <textarea id="oralNotes" 
                                              name="oralNotes" 
                                              rows="6"
                                              placeholder="Patient appearance, behavior, complaints, diagnosis, treatment plan, medications prescribed, follow-up instructions, etc."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent resize-vertical"></textarea>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span class="font-medium text-gray-700">Status:</span>
                                <select id="modalStatus" name="modalStatus" class="mt-1 px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent text-gray-900">
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Rescheduled">Rescheduled</option>
                                </select>
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

                <!-- Treatment Plan Section -->
                <div id="treatmentPlanSection" class="glass-card bg-white/50 border-gray-200 shadow-sm border-2 rounded-2xl p-6 mt-6 hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-nhd-blue">Treatment Plan</h4>
                        <button type="button" 
                                onclick="addTreatmentPlanItem()"
                                class="glass-card bg-green-100/80 text-green-800 px-3 py-2 rounded-2xl hover:bg-green-200/80 transition-colors text-sm">
                            Add Item
                        </button>
                    </div>

                    <form id="treatmentPlanForm">
                        <input type="hidden" id="treatmentPlanAppointmentReportId" name="appointmentReportID">
                        
                        <div class="mb-4">
                            <label for="treatmentPlanStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="treatmentPlanStatus" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="treatmentPlanNotes" class="block text-sm font-medium text-gray-700 mb-1">Dentist Notes</label>
                            <textarea id="treatmentPlanNotes" 
                                      name="dentistNotes" 
                                      rows="3"
                                      placeholder="Treatment plan notes and instructions..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-nhd-blue focus:border-transparent resize-vertical"></textarea>
                        </div>

                        <!-- Treatment Plan Items -->
                        <div class="mb-4">
                            <h5 class="text-base font-medium text-gray-800 mb-3">Treatment Items</h5>
                            <div id="treatmentPlanItems" class="space-y-3">
                                <!-- Items will be dynamically added here -->
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button type="button" 
                                    onclick="cancelTreatmentPlan()"
                                    class="px-4 py-2 text-gray-600 glass-card bg-gray-100/85 rounded-2xl hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2 glass-card bg-green-600/85 text-white font-semibold rounded-2xl hover:bg-opacity-90 transition-colors focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
                                Create Treatment Plan
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