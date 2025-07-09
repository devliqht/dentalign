<div class="px-4 pb-8">
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
                                Appointment History
                            </h1>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Total completed: <?php echo count(
                                        $appointmentHistory
                                    ); ?> appointments
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="<?php echo BASE_URL; ?>/doctor/schedule" class="glass-card bg-nhd-blue/80 text-white px-3 py-2 rounded-2xl text-sm font-medium hover:bg-nhd-blue transition-all duration-200">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Current Schedule
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <?php
                    $totalAppointments = count($appointmentHistory);
                    $thisMonth = 0;
                    $thisWeek = 0;
                    $currentMonth = date("Y-m");
                    $currentWeekStart = date(
                        "Y-m-d",
                        strtotime("monday this week")
                    );
                    $currentWeekEnd = date(
                        "Y-m-d",
                        strtotime("sunday this week")
                    );

                    foreach ($appointmentHistory as $appointment) {
                        $appointmentDate = date(
                            "Y-m-d",
                            strtotime($appointment["DateTime"])
                        );
                        $appointmentMonth = date(
                            "Y-m",
                            strtotime($appointment["DateTime"])
                        );

                        if ($appointmentMonth === $currentMonth) {
                            $thisMonth++;
                        }

                        if (
                            $appointmentDate >= $currentWeekStart &&
                            $appointmentDate <= $currentWeekEnd
                        ) {
                            $thisWeek++;
                        }
                    }
                    ?>
                    
                    <div class="glass-card bg-white/60 rounded-2xl p-6 shadow-none border-1 border-gray-300">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900"><?php echo $totalAppointments; ?></p>
                                <p class="text-gray-600">Total Completed</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card bg-white/60 rounded-2xl p-6 shadow-none border-1 border-gray-300">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900"><?php echo $thisMonth; ?></p>
                                <p class="text-gray-600">This Month</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="glass-card bg-white/60 rounded-2xl p-6 shadow-none border-1 border-gray-300">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900"><?php echo $thisWeek; ?></p>
                                <p class="text-gray-600">This Week</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment History Table -->
                <div class="bg-white/60 rounded-2xl border border-gray-200/50">
                    <div class="p-4 border-b border-gray-200/50">
                        <h3 class="text-2xl font-semibold text-nhd-brown">List of Appointments</h3>
                    </div>
                    
                    <?php if (!empty($appointmentHistory)): ?>
                        <!-- Mobile View -->
                        <div class="block lg:hidden">
                            <?php foreach (
                                $appointmentHistory
                                as $appointment
                            ): ?>
                                <div class="p-4 border-b border-gray-200/30 hover:bg-white/40 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded text-xs font-medium">
                                            <?php echo date(
                                                "M j",
                                                strtotime(
                                                    $appointment["DateTime"]
                                                )
                                            ); ?> • <?php echo date(
     "g:i A",
     strtotime($appointment["DateTime"])
 ); ?>
                                        </div>
                                        <span class="bg-green-100/60 text-green-800 px-2 py-1 rounded-full text-xs">Completed</span>
                                    </div>
                                    <h4 class="font-semibold text-gray-900 mb-1">
                                        <?php echo htmlspecialchars(
                                            $appointment["PatientFirstName"] .
                                                " " .
                                                $appointment["PatientLastName"]
                                        ); ?>
                                    </h4>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <?php echo htmlspecialchars(
                                            $appointment["PatientEmail"]
                                        ); ?>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm">
                                            <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded text-xs">
                                                <?php echo htmlspecialchars(
                                                    $appointment[
                                                        "AppointmentType"
                                                    ]
                                                ); ?>
                                            </span>
                                        </div>
                                        <div class="flex space-x-1">
                                            <button onclick="openAppointmentDetailsModal(<?php echo $appointment[
                                                "AppointmentID"
                                            ]; ?>)" 
                                                    class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                                Details
                                            </button>
                                            <button class="bg-gray-200/80 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300/80 transition-colors">
                                                Notes
                                            </button>
                                        </div>
                                    </div>
                                    <?php if (
                                        !empty($appointment["Reason"])
                                    ): ?>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <span class="font-medium">Reason:</span> <?php echo htmlspecialchars(
                                                $appointment["Reason"]
                                            ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div id="loading-state" style="display: none; padding: 20px; text-align: center;">
                            <em>Loading sorted data...</em>
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden lg:block overflow-x-auto">
                            <table class="w-full appoinment-history-table">
                                <thead>
                                <tr class="border-b border-gray-200/60 bg-gray-50/50">
                                    <!-- Add data-sort attribute and a span for the icon -->
                                    <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm" data-sort="DateTime">
                                        Date & Time <span class="sort-indicator"></span>
                                    </th>
                                    <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm" data-sort="PatientFirstName">
                                        Patient <span class="sort-indicator"></span>
                                    </th>
                                    <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm" data-sort="PatientEmail">
                                        Contact <span class="sort-indicator"></span>
                                    </th>
                                    <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm" data-sort="AppointmentType">
                                        Type <span class="sort-indicator"></span>
                                    </th>
                                    <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Reason</th> <!-- Not sortable -->
                                    <th class="sortable-header text-left py-2 px-3 font-medium text-gray-700 text-sm" data-sort="Status">
                                        Status <span class="sort-indicator"></span>
                                    </th>
                                    <th class="text-left py-2 px-3 font-medium text-gray-700 text-sm">Actions</th> <!-- Not sortable -->
                                </tr>
                                </thead>
                                <tbody id="appointment-history-table" class="divide-y divide-gray-200/30">
                                    <?php foreach (
                                        $appointmentHistory
                                        as $appointment
                                    ): ?>
                                        <tr class="hover:bg-white/40 transition-colors duration-200">
                                            <!-- Date & Time -->
                                            <td class="py-2 px-3">
                                                <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                                                    <div class="font-medium">
                                                        <?php echo date(
                                                            "M j",
                                                            strtotime(
                                                                $appointment[
                                                                    "DateTime"
                                                                ]
                                                            )
                                                        ); ?>
                                                    </div>
                                                    <div class="font-bold">
                                                        <?php echo date(
                                                            "g:i A",
                                                            strtotime(
                                                                $appointment[
                                                                    "DateTime"
                                                                ]
                                                            )
                                                        ); ?>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Patient Name -->
                                            <td class="py-2 px-3">
                                                <div class="font-medium text-gray-900 text-sm">
                                                    <?php echo htmlspecialchars(
                                                        $appointment[
                                                            "PatientFirstName"
                                                        ] .
                                                            " " .
                                                            $appointment[
                                                                "PatientLastName"
                                                            ]
                                                    ); ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    ID #<?php echo str_pad(
                                                        $appointment[
                                                            "AppointmentID"
                                                        ],
                                                        4,
                                                        "0",
                                                        STR_PAD_LEFT
                                                    ); ?>
                                                </div>
                                            </td>
                                            
                                            <!-- Contact -->
                                            <td class="py-2 px-3">
                                                <div class="text-sm text-gray-600">
                                                    <?php echo htmlspecialchars(
                                                        $appointment[
                                                            "PatientEmail"
                                                        ]
                                                    ); ?>
                                                </div>
                                            </td>
                                            
                                            <!-- Appointment Type -->
                                            <td class="py-2 px-3">
                                                <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                                    <?php echo htmlspecialchars(
                                                        $appointment[
                                                            "AppointmentType"
                                                        ]
                                                    ); ?>
                                                </span>
                                            </td>
                                            
                                            <!-- Reason -->
                                            <td class="py-2 px-3 max-w-xs">
                                                <?php if (
                                                    !empty(
                                                        $appointment["Reason"]
                                                    )
                                                ): ?>
                                                    <div class="text-sm text-gray-600 truncate" title="<?php echo htmlspecialchars(
                                                        $appointment["Reason"]
                                                    ); ?>">
                                                        <?php echo htmlspecialchars(
                                                            $appointment[
                                                                "Reason"
                                                            ]
                                                        ); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-gray-400 text-sm italic">-</span>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <!-- Status -->
                                            <td class="py-2 px-3">
                                                <span class="bg-green-100/60 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                                    <?php echo htmlspecialchars($appointment["Status"]);?>
                                                </span>
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td class="py-2 px-3">
                                                <div class="flex space-x-1">
                                                    <button onclick="openAppointmentDetailsModal(<?php echo $appointment[
                                                        "AppointmentID"
                                                    ]; ?>)" 
                                                            class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">
                                                        Details
                                                    </button>
                                                    <button class="bg-gray-200/80 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300/80 transition-colors">
                                                        Notes
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination placeholder -->
                        <?php if (count($appointmentHistory) > 10): ?>
                            <div class="mt-8 flex justify-center">
                                <div class="glass-card bg-white/60 rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Showing first <?php echo min(
                                        50,
                                        count($appointmentHistory)
                                    ); ?> appointments</p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <!-- Empty state -->
                        <div class="text-center py-12">
                            <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No appointment history yet</h3>
                            <p class="text-gray-500 mb-4">Your completed appointments will appear here once you start seeing patients.</p>
                            <a href="<?php echo BASE_URL; ?>/doctor/schedule" class="glass-card bg-nhd-blue/80 text-white px-6 py-2 rounded-lg font-medium hover:bg-nhd-blue transition-colors">
                                View Current Schedule
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
</div>

<!-- Appointment Details Modal -->
<?php include "app/views/components/SchedulePage/AppointmentDetailsModal.php"; ?>
<style>
    .sortable-header {
        cursor: pointer;
        user-select: none; /* Prevents text selection on click */
    }
    .sortable-header:hover {
        background-color: #f0f0f0; /* Or your preferred hover color */
    }
    .sort-indicator {
        display: inline-block;
        width: 1em;
        text-align: left;
    }
</style>
<script>
    // --- STATE MANAGEMENT ---
    let currentSort = {
        key: 'DateTime', // Default sort key
        direction: 'desc' // Default sort direction
    };

    // --- DOM REFERENCES ---
    const tableBody = document.getElementById('appointment-history-table');
    const loadingState = document.getElementById('loading-state');
    const sortableHeaders = document.querySelectorAll('.sortable-header');

    // --- EVENT LISTENERS ---
    document.addEventListener('DOMContentLoaded', () => {
        updateSortIndicators();
    });

    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const sortKey = this.dataset.sort;

            if (currentSort.key === sortKey) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.key = sortKey;
                currentSort.direction = 'asc';
            }
            fetchSortedData(currentSort.key, currentSort.direction);
        });
    });

    // --- AJAX AND DOM MANIPULATION ---
    async function fetchSortedData(sortOption, sortDirection) {
        loadingState.style.display = 'block';
        tableBody.style.opacity = '0.5';

        try {
            const url = `${window.BASE_URL}/doctor/sortAppointmentHistory/${sortOption}/${sortDirection}`;
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            
            const sortedAppointments = await response.json();
            
            renderTable(sortedAppointments);
            updateSortIndicators();

        } catch (error) {
            console.error("Failed to fetch or sort data:", error);
            tableBody.innerHTML = `<tr><td colspan="7">Error loading data. Please try again.</td></tr>`;
        } finally {
            loadingState.style.display = 'none';
            tableBody.style.opacity = '1';
        }
    }

    /**
     * CORRECTED HELPER FUNCTION
     * Safely escapes HTML special characters to prevent XSS.
     * @param {string} unsafe - The string to escape.
     * @returns {string} The escaped string.
     */
    const escapeHtml = (unsafe) => {
        if (typeof unsafe !== 'string') return ''; // Return empty string for non-string types
        return unsafe.replace(/[&<>"']/g, m => ({
            '&': '&',
            '<': '<',
            '>': '>',
            '"': '"',
            "'": '' // Replaces single quote with its HTML entity
        }[m]));
    };

    function renderTable(appointments) {
        tableBody.innerHTML = '';

        if (appointments.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="7">No appointment history found.</td></tr>`;
            return;
        }
        
        const rowsHtml = appointments.map(appt => {
            const appointmentDate = new Date(appt.DateTime);
            const formattedDate = appointmentDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const formattedTime = appointmentDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
            
            const reasonHtml = appt.Reason 
                ? `<div class="text-sm text-gray-600 truncate" title="${escapeHtml(appt.Reason)}">${escapeHtml(appt.Reason)}</div>`
                : `<span class="text-gray-400 text-sm italic">-</span>`;

            return `
                <tr class="hover:bg-white/40 transition-colors duration-200">
                    <td class="py-2 px-3">
                        <div class="bg-nhd-blue/10 text-nhd-blue px-2 py-1 rounded inline-block text-xs">
                            <div class="font-medium">${formattedDate}</div>
                            <div class="font-bold">${formattedTime}</div>
                        </div>
                    </td>
                    <td class="py-2 px-3">
                        <div class="font-medium text-gray-900 text-sm">${escapeHtml(appt.PatientFirstName + ' ' + appt.PatientLastName)}</div>
                        <div class="text-xs text-gray-500">ID #${String(appt.AppointmentID).padStart(4, '0')}</div>
                    </td>
                    <td class="py-2 px-3">
                        <div class="text-sm text-gray-600">${escapeHtml(appt.PatientEmail)}</div>
                    </td>
                    <td class="py-2 px-3">
                        <span class="bg-gray-100/60 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">${escapeHtml(appt.AppointmentType)}</span>
                    </td>
                    <td class="py-2 px-3 max-w-xs">${reasonHtml}</td>
                    <td class="py-2 px-3">
                        <span class="bg-green-100/60 text-green-800 px-2 py-1 rounded-full text-xs font-medium">${escapeHtml(appt.Status)}</span>
                    </td>
                    <td class="py-2 px-3">
                        <div class="flex space-x-1">
                            <button onclick="openAppointmentDetailsModal(${appt.AppointmentID})" class="bg-nhd-blue/80 text-white px-2 py-1 rounded text-xs hover:bg-nhd-blue transition-colors">Details</button>
                            <button class="bg-gray-200/80 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-300/80 transition-colors">Notes</button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        tableBody.innerHTML = rowsHtml;
    }

    function updateSortIndicators() {
        sortableHeaders.forEach(header => {
            const indicator = header.querySelector('.sort-indicator');
            if (header.dataset.sort === currentSort.key) {
                indicator.textContent = currentSort.direction === 'asc' ? ' ▲' : ' ▼';
            } else {
                indicator.textContent = '';
            }
        });
    }
</script>