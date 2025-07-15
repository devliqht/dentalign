<div id="today-section" class="schedule-section">
        <div class="mb-4">
            <h3 class="text-2xl font-semibold text-nhd-brown mb-2">Today's Appointments</h3>
            <p class="text-gray-600 text-sm"><?php echo date(
                "l, F j, Y"
            ); ?></p>
        </div>
        
        <?php if (!empty($todaysAppointments)): ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <?php foreach ($todaysAppointments as $appointment): ?>
                    <div class="glass-card rounded-2xl shadow-lg border-2 border-gray-300/70 p-6 hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group">
                        <!-- Time Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="glass-card bg-nhd-blue/10 text-nhd-blue px-3 py-2 rounded-xl">
                                <div class="text-xs font-medium uppercase tracking-wider">Appointment Time</div>
                                <div class="text-lg font-bold">
                                    <?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </div>
                            </div>
                            <div class="glass-card bg-green-100/60 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                ID #<?php echo str_pad(
                                    $appointment["AppointmentID"],
                                    4,
                                    "0",
                                    STR_PAD_LEFT
                                ); ?>
                            </div>
                        </div>

                        <!-- Patient Information -->
                        <div class="space-y-3">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-nhd-blue transition-colors">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientFirstName"] .
                                            " " .
                                            $appointment["PatientLastName"]
                                    ); ?>
                                </h4>
                                <p class="text-sm text-gray-500">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientEmail"]
                                    ); ?>
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Type:</span>
                                    <p class="text-gray-600"><?php echo htmlspecialchars(
                                       $appointment["AppointmentType"]
                                    ); ?></p>
                                </div>
                            </div>

                            <div>
                                <span class="font-medium text-gray-700">Reason:</span>
                                <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars(
                                    $appointment["Reason"]
                                ); ?></p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
                            <button onclick="openAppointmentDetailsModal(<?php echo $appointment[
                                "AppointmentID"
                            ]; ?>)" 
                                    class="glass-card bg-nhd-blue/80 text-white px-3 py-1 rounded-lg text-xs hover:bg-nhd-blue transition-colors">
                                View Details
                            </button>
                            <button class="glass-card bg-gray-200/80 text-gray-700 px-3 py-1 rounded-lg text-xs hover:bg-gray-300/80 transition-colors">
                                Add Notes
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card shadow-sm rounded-2xl p-8 text-center border-2 border-gray-200">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No appointments today</h3>
                <p class="text-gray-500">You have a free day! Enjoy your time off.</p>
            </div>
        <?php endif; ?>

        <!-- Week View Section -->
        <div class="mt-8">
            <div class="mb-4">
                <div class="flex items-center justify-between">
                    <h3 id="week-header" class="text-2xl font-semibold text-nhd-brown">Week of <?php echo date(
                        "M j",
                        strtotime($startOfWeek)
                    ); ?> - <?php echo date(
                        "M j, Y",
                        strtotime($endOfWeek)
                    ); ?></h3>
                    <div class="flex space-x-2">
                        <button onclick="navigateWeekSimple(-1)" class="glass-card bg-gray-200/80 px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-300/80 transition-colors">
                            ← Previous Week
                        </button>
                        <button onclick="navigateWeekSimple(1)" class="glass-card bg-gray-200/80 px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-300/80 transition-colors">
                            Next Week →
                        </button>
                    </div>
                </div>
            </div>

            <div id="week-grid" class="grid grid-cols-1 md:grid-cols-7 gap-4">
                <?php
                $daysOfWeek = [
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday",
                    "Sunday",
                ];
            for ($i = 0; $i < 7; $i++):

                $currentDate = date(
                    "Y-m-d",
                    strtotime($startOfWeek . " +" . $i . " days")
                );
                $dayAppointments = array_filter(
                    $weekAppointments,
                    function ($app) use ($currentDate) {
                        return date(
                            "Y-m-d",
                            strtotime($app["DateTime"])
                        ) === $currentDate;
                    }
                );
                $isToday = $currentDate === date("Y-m-d");
                ?>
                <div class="glass-card rounded-2xl shadow-sm p-4 <?php echo $isToday
                ? "bg-nhd-blue/10 border-1 border-nhd-blue/30"
                : "bg-white/60 border-gray-200 border-1"; ?>">
                    <div class="text-center mb-3">
                        <h4 class="font-semibold text-gray-900 <?php echo $isToday
                        ? "text-nhd-blue"
                        : ""; ?>">
                            <?php echo $daysOfWeek[$i]; ?>
                        </h4>
                        <p class="text-sm text-gray-600 <?php echo $isToday
                        ? "text-nhd-blue/80"
                        : ""; ?>">
                            <?php echo date("M j", strtotime($currentDate)); ?>
                            <?php if (
                                $isToday
                            ): ?><span class="text-xs">(Today)</span><?php endif; ?>
                        </p>
                    </div>
                    
                    <?php if (!empty($dayAppointments)): ?>
                        <div class="space-y-2">
                            <?php foreach ($dayAppointments as $appointment): ?>
                                <div class="glass-card bg-white/40 p-3 border-gray-200 border-1 shadow-sm rounded-xl text-xs cursor-pointer hover:bg-white/60 transition-colors group" 
                                     onclick="openAppointmentDetailsModal(<?php echo $appointment['AppointmentID']; ?>)"
                                     title="Click to view appointment details">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="font-semibold text-nhd-blue group-hover:text-nhd-blue/80">
                                            <?php echo date(
                                                "g:i A",
                                                strtotime($appointment["DateTime"])
                                            ); ?>
                                        </div>
                                        <div class="text-xs text-gray-400 group-hover:text-gray-500">
                                            #<?php echo str_pad(
                                                $appointment["AppointmentID"],
                                                4,
                                                "0",
                                                STR_PAD_LEFT
                                            ); ?>
                                        </div>
                                    </div>
                                    <div class="text-gray-900 font-medium group-hover:text-gray-700">
                                        <?php echo htmlspecialchars(
                                            $appointment["PatientFirstName"] .
                                                " " .
                                                $appointment["PatientLastName"]
                                        ); ?>
                                    </div>
                                    <div class="text-gray-600 truncate group-hover:text-gray-500">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-400 group-hover:text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                        Click for details →
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-gray-400 text-xs py-4">
                            No appointments
                        </div>
                    <?php endif; ?>
                </div>
                <?php
            endfor;
            ?>
            </div>
        </div>
    </div>

    <script>
    // AJAX week navigation for the integrated week view (scoped variables to avoid conflicts)
    let todayWeekOffset = 0;
    let todayOriginalWeekStart = new Date('<?php echo $startOfWeek; ?>');
    let todayWeekLoading = false;

    function navigateWeekSimple(direction) {
        if (todayWeekLoading) return; // Prevent multiple simultaneous requests
        
        todayWeekOffset += direction;
        todayWeekLoading = true;
        
        // Calculate new week start
        const newWeekStart = new Date(todayOriginalWeekStart);
        newWeekStart.setDate(todayOriginalWeekStart.getDate() + todayWeekOffset * 7);
        
        const formattedDate = newWeekStart.toISOString().split('T')[0];
        
        const weekGrid = document.getElementById('week-grid');
        const weekHeader = document.getElementById('week-header');
        weekGrid.style.opacity = '0.9';
        
        fetch(`<?php echo BASE_URL; ?>/doctor/get-week-data?date=${formattedDate}`)
            .then(response => response.json())
            .then(data => {
                weekGrid.innerHTML = data.html;
                
                weekHeader.textContent = `Week of ${data.dateRange}`;
                
                weekGrid.style.opacity = '1';
                todayWeekLoading = false;
            })
            .catch(error => {
                console.error('Error loading week data:', error);
                
                weekGrid.style.opacity = '1';
                todayWeekLoading = false;
                
                weekGrid.innerHTML = '<div class="col-span-7 text-center text-red-500 py-4">Error loading week data. Please try again.</div>';
            });
    }
    </script>
