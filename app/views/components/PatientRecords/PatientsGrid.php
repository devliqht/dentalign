<div id="patients-grid" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($patients as $patient): ?>
            <div class="patient-card glass-card rounded-2xl shadow-sm border-gray-200 p-6 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 group" 
                 data-patient-name="<?php echo strtolower(
                     htmlspecialchars(
                         $patient["FirstName"] . " " . $patient["LastName"]
                     )
                 ); ?>"
                 data-patient-email="<?php echo strtolower(
                     htmlspecialchars($patient["Email"])
                 ); ?>"
                 data-has-record="<?php echo $patient["RecordID"]
                     ? "true"
                     : "false"; ?>">
                
                <!-- Patient Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 group-hover:text-nhd-blue transition-colors">
                            <?php echo htmlspecialchars(
                                $patient["FirstName"] .
                                    " " .
                                    $patient["LastName"]
                            ); ?>
                        </h3>
                        <p class="text-sm text-gray-500 mb-1">
                            <?php echo htmlspecialchars($patient["Email"]); ?>
                        </p>
                        <p class="text-xs text-gray-400">
                            Patient ID: #<?php echo str_pad(
                                $patient["PatientID"],
                                4,
                                "0",
                                STR_PAD_LEFT
                            ); ?>
                        </p>
                    </div>
                    <div class="glass-card <?php echo $patient["RecordID"]
                        ? "bg-green-100/60 text-green-800"
                        : "bg-yellow-100/60 text-yellow-800"; ?> px-3 py-1 rounded-full text-xs font-medium">
                        <?php echo $patient["RecordID"]
                            ? "Complete"
                            : "Incomplete"; ?>
                    </div>
                </div>

                <!-- Patient Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-nhd-blue">
                            <?php echo $patient["TotalAppointments"] ?: "0"; ?>
                        </div>
                        <div class="text-xs text-gray-500">Appointments</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-semibold text-gray-700">
                            <?php if ($patient["LastAppointment"]) {
                                $lastAppt = new DateTime(
                                    $patient["LastAppointment"]
                                );
                                echo $lastAppt->format("M j, Y");
                            } else {
                                echo "Never";
                            } ?>
                        </div>
                        <div class="text-xs text-gray-500">Last Visit</div>
                    </div>
                </div>

                <!-- Medical Record Summary -->
                <?php if ($patient["RecordID"]): ?>
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Medical Record
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Height:</span>
                                <span class="font-medium"><?php echo $patient[
                                    "Height"
                                ]
                                    ? $patient["Height"] . " cm"
                                    : "Not recorded"; ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Weight:</span>
                                <span class="font-medium"><?php echo $patient[
                                    "Weight"
                                ]
                                    ? $patient["Weight"] . " kg"
                                    : "Not recorded"; ?></span>
                            </div>
                            <div class="text-sm">
                                <span class="text-gray-600">Allergies:</span>
                                <div class="mt-1">
                                    <?php if ($patient["Allergies"]): ?>
                                        <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs">
                                            <?php echo htmlspecialchars(
                                                $patient["Allergies"]
                                            ); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-xs">None recorded</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <span class="text-sm text-yellow-800">No medical record found</span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Registration Info -->
                <div class="text-xs text-gray-500 mb-4">
                    Registered: <?php echo date(
                        "M j, Y",
                        strtotime($patient["PatientCreatedAt"])
                    ); ?>
                    <?php if ($patient["RecordCreatedAt"]): ?>
                        <br>Record created: <?php echo date(
                            "M j, Y",
                            strtotime($patient["RecordCreatedAt"])
                        ); ?>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-2">
                    <div class="flex gap-2">
                        <button class="flex-1 glass-card bg-nhd-blue/80 text-white px-3 py-2 rounded-2xl text-sm hover:bg-nhd-blue transition-colors"
                                onclick="viewPatientDetail(<?php echo $patient[
                                            "PatientID"
                                        ]; ?>)">
                            View Details
                        </button>
                        <?php if ($patient["RecordID"]): ?>
                            <button class="glass-card bg-gray-200/80 text-gray-700 px-3 py-2 rounded-2xl text-sm shadow-sm hover:bg-gray-300/80 transition-colors"
                                    onclick="editPatientRecord(<?php echo $patient[
                                                "RecordID"
                                            ]; ?>, <?php echo $patient[
    "PatientID"
]; ?>)">
                                Edit Record
                            </button>
                        <?php else: ?>
                            <button class="glass-card bg-green-200/80 text-green-700 px-3 py-2 rounded-2xl text-sm hover:bg-green-300/80 transition-colors"
                                    onclick="createPatientRecord(<?php echo $patient[
                                                "PatientID"
                                            ]; ?>)">
                                Create Record
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Dental Chart Button -->
                    <button class="w-full glass-card bg-nhd-green/80 text-white px-3 py-2 rounded-2xl text-sm hover:bg-nhd-green shadow-sm transition-colors flex items-center justify-center"
                            onclick="editDentalChart(<?php echo $patient[
                                        "PatientID"
                                    ]; ?>)">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Edit Dental Chart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>