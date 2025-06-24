<div class="px-4 pb-8">
    <div class="pb-6">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">Book Appointment</h2>
        <p class="text-gray-600">Schedule your dental appointment with one of our experienced doctors.</p>
    </div>

    <div class="glass-card rounded-2xl p-6">
        <form method="POST" action="<?php echo BASE_URL; ?>/patient/book-appointment" class="space-y-6" id="appointment-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(
                $csrf_token
            ); ?>">
            
            <!-- Doctor Selection -->
            <div class="form-group">
                <label class="block text-2xl font-medium tracking-tight font-family-bodoni text-gray-700 mb-4 text-shadow-sm">Select Your Doctor</label>
                <input type="hidden" id="doctor_id" name="doctor_id" value="<?php echo htmlspecialchars(
                    $selectedDoctorId
                ); ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($doctors as $doctor): ?>
                        <div class="doctor-card relative flex flex-col flex-end justify-end border glass-card rounded-2xl p-4 cursor-pointer transition-all duration-300 hover:shadow-lg group <?php echo $selectedDoctorId ==
                        $doctor["UserID"]
                            ? "selected border-nhd-brown shadow-lg"
                            : ""; ?>" 
                             data-doctor-id="<?php echo $doctor["UserID"]; ?>">
                            
                            <!-- Selection Indicator -->
                            <div class="absolute top-3 right-3 w-6 h-6 rounded-full border-2 border-gray-300 group-hover:border-nhd-brown transition-colors duration-300 <?php echo $selectedDoctorId ==
                            $doctor["UserID"]
                                ? "bg-nhd-brown border-nhd-brown"
                                : ""; ?>">
                                <div class="w-full h-full rounded-full bg-white scale-50 transition-transform duration-300 <?php echo $selectedDoctorId ==
                                $doctor["UserID"]
                                    ? "scale-75"
                                    : ""; ?>"></div>
                            </div>
                            
                            <!-- Doctor Avatar -->
                            <div class="flex flex-col">
                                
                                <!-- Doctor Name -->
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                    Dr. <?php echo htmlspecialchars(
                                        $doctor["FirstName"] .
                                            " " .
                                            $doctor["LastName"]
                                    ); ?>
                                </h3>
                                
                                <!-- Specialization -->
                                <p class="text-sm text-gray-600 font-family-bodoni tracking-tight font-light mb-3">
                                    <?php echo htmlspecialchars(
                                        $doctor["Specialization"]
                                    ); ?>
                                </p>
                            </div>
                            
                            <!-- Hover Effect Overlay -->
                            <div class="absolute inset-0 bg-nhd-brown opacity-0 group-hover:opacity-5 rounded-xl transition-opacity duration-300"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                </div>

            <!-- Date & Time Selection -->
            <div class="form-group">
                <label class="block text-2xl font-medium tracking-tight font-family-bodoni text-gray-700 mb-4 text-shadow-sm">Select Appointment Date & Time</label>
                <input type="hidden" id="appointment_date" name="appointment_date" value="<?php echo htmlspecialchars(
                    $selectedDate
                ); ?>">
                <input type="hidden" id="appointment_time" name="appointment_time" value="">
                
                <div class="flex flex-col lg:flex-row gap-6 w-full">
                    <!-- Calendar Section -->
                    <div class="flex-shrink-0">
                        <h3 class="text-base font-medium text-gray-800 mb-3">Choose Date</h3>
                        <div class="w-[320px] glass-card border border-gray-200 rounded-xl shadow-sm p-4">
                            <div class="flex items-center justify-between mb-4">
                                <button type="button" id="prev-month" class="glass-card bg-nhd-blue/80">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <h3 id="calendar-month-year" class="text-lg font-semibold text-gray-800"></h3>
                                <button type="button" id="next-month" class="glass-card bg-nhd-blue/80">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Days of Week -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Sun</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Mon</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Tue</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Wed</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Thu</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Fri</div>
                                <div class="text-center text-xs font-medium text-gray-500 py-2">Sat</div>
                            </div>
                            
                            <!-- Calendar Days -->
                            <div id="calendar-days" class="grid grid-cols-7 gap-1">
                                <!-- Days will be generated by JavaScript -->
                            </div>
                            
                            <!-- Selected Date Display -->
                            <div class="mt-4 p-3 glass-card rounded-2xl">
                                <p class="text-sm text-gray-600">Selected Date:</p>
                                <p id="selected-date-display" class="text-base font-medium text-nhd-brown">No date selected</p>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots Section -->
                    <div class="flex flex-col flex-1 gap-4">
                        <div class="rounded-lg p-4">
                        <div class="flex items-center gap-4 mb-4">
                                <label class="text-base font-semibold tracking-tight text-nhd-blue whitespace-nowrap">Choose Time</label>
                                <div class="h-[1px] bg-nhd-blue w-full"></div>
                            </div>
                            <div id="time-slots-container" class="flex flex-wrap gap-3 w-full">
                                 <?php if (empty($timeSlots)): ?>
                                     <div class="w-full text-center py-8">
                                         <p class="text-gray-500 text-sm">Please select a doctor and date to view available time slots.</p>
                                     </div>
                                 <?php endif; ?>
                             </div>
                            
                            <!-- Selected Time Display -->
                            <div class="mt-4 p-3 glass-card rounded-2xl">
                                <p class="text-sm text-gray-600">Selected Time:</p>
                                <p id="selected-time-display" class="text-base font-medium text-nhd-brown">No time selected</p>
                            </div>
                        </div>

                        <!-- Appointment Type -->
                        <div class="form-group px-4">
                            <div class="flex items-center gap-4 mb-4">
                                <label class="text-base font-semibold tracking-tight text-nhd-blue whitespace-nowrap">Appointment Type</label>
                                <div class="h-[1px] bg-nhd-blue w-full"></div>
                            </div>
                            <input type="hidden" id="appointment_type" name="appointment_type" value="">
                            
                            <div id="appointment-types-container" class="flex flex-wrap gap-3 w-full">
                                <!-- Consultation -->
                                <div class="appointment-type-card glass-card px-4 py-3 text-sm font-medium text-gray-700 border border-gray-200 rounded-2xl hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none cursor-pointer" 
                                     data-type="Consultation">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <span>Consultation</span>
                                    </div>
                                </div>
                                
                                <!-- Cleaning -->
                                <div class="appointment-type-card glass-card px-4 py-3 text-sm font-medium text-gray-700 border border-gray-200 rounded-2xl hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none cursor-pointer" 
                                     data-type="Cleaning">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Cleaning</span>
                                    </div>
                                </div>
                                
                                <!-- Filling -->
                                <div class="appointment-type-card glass-card px-4 py-3 text-sm font-medium text-gray-700 border border-gray-200 rounded-2xl hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none cursor-pointer" 
                                     data-type="Filling">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Filling</span>
                                    </div>
                                </div>
                                
                                <!-- Root Canal -->
                                <div class="appointment-type-card glass-card px-4 py-3 text-sm font-medium text-gray-700 border border-gray-200 rounded-2xl hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none cursor-pointer" 
                                     data-type="Root Canal">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Root Canal</span>
                                    </div>
                                </div>
                                
                                <!-- Extraction -->
                                <div class="appointment-type-card glass-card px-4 py-3 text-sm font-medium text-gray-700 border border-gray-200 rounded-2xl hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none cursor-pointer" 
                                     data-type="Extraction">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span>Extraction</span>
                                    </div>
                                </div>
                                
                                <!-- Orthodontics -->
                                <div class="appointment-type-card glass-card px-4 py-3 text-sm font-medium text-gray-700 border border-gray-200 rounded-2xl hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none cursor-pointer" 
                                     data-type="Orthodontics">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span>Orthodontics</span>
                                    </div>
                                </div>
                                
                                <!-- Emergency -->
                                <div class="appointment-type-card glass-card px-4 py-3 text-sm font-medium text-gray-700 border border-gray-200 rounded-2xl hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none cursor-pointer" 
                                     data-type="Emergency">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <span>Emergency</span>
                                    </div>
                                </div>
                                
                                <!-- Follow-up -->
                                <div class="appointment-type-card glass-card px-4 py-3 text-sm font-medium text-gray-700 border border-gray-200 rounded-2xl hover:bg-nhd-brown/85 hover:text-white hover:border-nhd-brown transition-all duration-200 focus:outline-none cursor-pointer" 
                                     data-type="Follow-up">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        <span>Follow-up</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Selected Appointment Type Display -->
                            <div class="mt-4 p-3 glass-card rounded-2xl">
                                <p class="text-sm text-gray-600">Selected Type:</p>
                                <p id="selected-type-display" class="text-base font-medium text-nhd-brown">No type selected</p>
                            </div>
                        </div>

                        <!-- Reason for Visit -->
                        <div class="form-group px-4">
                            <label for="reason" class="block text-base font-bold text-nhd-blue mb-2">Reason for Visit</label>
                            <textarea id="reason" 
                                    name="reason" 
                                    rows="5" 
                                    required 
                                    placeholder="Please describe your symptoms or the reason for your visit..."
                                    ></textarea>
                            <p class="text-sm text-gray-500 mt-1">Minimum 10 characters required.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-4">
                <a href="<?php echo BASE_URL; ?>/patient/dashboard" 
                   class="px-4 py-2 text-gray-600 glass-card bg-gray-100/85 rounded-2xl hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 glass-card bg-nhd-brown/85 text-white font-semibold rounded-2xl hover:bg-opacity-90 transition-colors focus:outline-none focus:ring-2 focus:ring-nhd-brown focus:ring-offset-2">
                    Book Appointment
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 bg-blue-50/40 glass-card rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-nhd-blue mb-3">Appointment Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-nhd-blue">
            <div>
                <h4 class="font-medium mb-2">Clinic Hours:</h4>
                <ul class="space-y-1">
                    <li>Monday - Friday: 8:00 AM - 6:00 PM</li>
                    <li>Emergency services available 24/7</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium mb-2">Important Notes:</h4>
                <ul class="space-y-1">
                    <li>• Please arrive 15 minutes early</li>
                    <li>• Bring your ID and insurance card</li>
                    <li>• Cancel at least 24 hours in advance</li>
                </ul>
            </div>
        </div>
    </div>
</div>

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

