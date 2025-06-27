<div id="week-section" class="schedule-section hidden">
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
                    <button onclick="navigateWeek(-1)" class="glass-card bg-gray-200/80 px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-300/80 transition-colors">
                        ← Previous Week
                    </button>
                    <button onclick="navigateWeek(1)" class="glass-card bg-gray-200/80 px-3 py-1 rounded-lg text-sm text-gray-700 hover:bg-gray-300/80 transition-colors">
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
                $dayAppointments = array_filter($weekAppointments, function (
                    $app
                ) use ($currentDate) {
                    return date("Y-m-d", strtotime($app["DateTime"])) ===
                        $currentDate;
                });
                $isToday = $currentDate === date("Y-m-d");
                ?>
            <div class="glass-card rounded-2xl p-4 <?php echo $isToday
                ? "bg-nhd-blue/10 border-2 border-nhd-blue/30"
                : "bg-white/60 border-gray-200 border-2"; ?>">
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
                            <div class="glass-card bg-white/40 p-3 rounded-xl text-xs">
                                <div class="font-semibold text-nhd-blue">
                                    <?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?>
                                </div>
                                <div class="text-gray-900 font-medium">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientFirstName"] .
                                            " " .
                                            $appointment["PatientLastName"]
                                    ); ?>
                                </div>
                                <div class="text-gray-600 truncate">
                                    <?php echo htmlspecialchars(
                                        $appointment["AppointmentType"]
                                    ); ?>
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