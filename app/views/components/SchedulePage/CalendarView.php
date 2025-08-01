<div id="calendar-section" class="schedule-section hidden">
        <div class="mb-4">
            <h3 class="text-2xl font-semibold text-nhd-brown mb-2">Calendar View</h3>
            <p class="text-gray-600 text-sm">Navigate through your schedule month by month</p>
        </div>
        
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Calendar -->
            <div class="flex-shrink-0">
                <div class="w-[320px] glass-card border border-gray-200 rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <button type="button" id="prev-month" class="glass-card bg-nhd-blue/80 text-white p-2 rounded-lg hover:bg-nhd-blue/90 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <h3 id="calendar-month-year" class="text-lg font-semibold text-gray-800"></h3>
                        <button type="button" id="next-month" class="glass-card bg-nhd-blue/80 text-white p-2 rounded-lg hover:bg-nhd-blue/90 transition-colors">
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
                </div>
            </div>

            <!-- Selected Date Appointments -->
            <div class="flex-1">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-nhd-brown mb-4">
                        Appointments for <span id="calendar-selected-date-title">Today</span>
                    </h3>
                    <button onclick="openBlockScheduleModal()"
                        class="glass-card bg-red-600/80 text-white px-4 py-2 text-sm font-semibold rounded-2xl hover:bg-red-700/80 transition-colors">
                        Block Schedule
                    </button>
                </div>
            
                <div id="calendar-appointments-container" class="space-y-4">
                    <!-- Appointments will be loaded by JavaScript -->
                </div>
            </div>
        </div>
    </div>