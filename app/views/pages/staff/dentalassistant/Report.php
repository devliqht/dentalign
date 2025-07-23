<div class="mx-auto px-4 pb-4">
    <div class="mb-8">
        <h2 class="text-4xl font-bold text-nhd-brown mb-2 font-family-bodoni tracking-tight">
            Reports & Analytics Dashboard
        </h2>
        <p class="text-gray-600 mb-4">
            Comprehensive overview of clinic performance, revenue, and appointment analytics.
        </p>
    </div>

    <!-- Summary Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <!-- Filtered Revenue Card -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm cursor-pointer hover:shadow-md transition-shadow hover:border-nhd-blue" onclick="scrollToRevenueBreakdown(getCurrentRevenueFilter())">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-2">
                    <p class="text-sm font-medium text-nhd-blue/80">Revenue</p>
                    <select id="revenue-filter" class="text-xs bg-transparent border border-gray-300 rounded px-2 py-1 text-nhd-blue focus:outline-none focus:ring-1 focus:ring-nhd-blue" onchange="updateRevenueDisplay()" onclick="event.stopPropagation()">
                        <option value="total">All Time</option>
                        <option value="monthly">This Month</option>
                        <option value="weekly">This Week</option>
                        <option value="today">Today</option>
                    </select>
                </div>
                <div class="p-3 bg-green-500/20 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p id="revenue-amount" class="text-3xl font-bold text-nhd-blue">₱<?php echo number_format(
                    $summary["revenue"]["total"],
                    2
                ); ?></p>
                <p id="revenue-period" class="text-xs text-gray-500 mt-1">All time revenue</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

 <!-- Total Appointments -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm cursor-pointer hover:border-nhd-blue" onclick="window.location.href='<?php echo BASE_URL; ?>/dentalassistant/appointment-history'">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-nhd-blue/80">Total Appointments</p>
                    <p class="text-3xl font-bold text-nhd-blue"><?php echo number_format(
                        $summary["appointments"]["total"]
                    ); ?></p>
                </div>
                <div class="p-3 bg-nhd-blue/20 rounded-full">
                    <svg class="w-6 h-6 text-nhd-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-nhd-blue/80">Total Patients</p>
                    <p class="text-3xl font-bold text-nhd-blue"><?php echo number_format(
                        $summary["patients"]["total"]
                    ); ?></p>
                </div>
                <div class="p-3 bg-purple-500/20 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm cursor-pointer hover:border-nhd-blue" onclick="window.location.href='<?php echo BASE_URL; ?>/dentalassistant/appointment-history'">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Today's Appointments</p>
                <p class="text-2xl font-bold text-nhd-blue"><?php echo $summary[
                    "appointments"
                ]["today"]; ?></p>
            </div>
        </div>

        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm cursor-pointer hover:border-nhd-blue" onclick="window.location.href='<?php echo BASE_URL; ?>/dentalassistant/appointment-history'">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Pending Appointments</p>
                <p class="text-2xl font-bold text-orange-600"><?php echo $summary[
                    "appointments"
                ]["pending"]; ?></p>
            </div>
        </div>

        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm cursor-pointer hover:border-nhd-blue" onclick="window.location.href='<?php echo BASE_URL; ?>/dentalassistant/payment-management'">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600 mb-2">Overdue Payments</p>
                <p class="text-2xl font-bold text-red-600"><?php echo $summary[
                    "payments"
                ]["overdue"]["count"]; ?></p>
                <p class="text-sm text-gray-500">₱<?php echo number_format(
                    $summary["payments"]["overdue"]["amount"],
                    2
                ); ?></p>
            </div>
        </div>
        </div>
       
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Daily Appointments Trend -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-nhd-blue mb-4">Daily Appointments (Last 30 Days)</h3>
            <div class="relative h-80">
                <canvas id="dailyAppointmentsChart"></canvas>
            </div>
        </div>

        <!-- Appointment Types Chart -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-nhd-blue mb-4">Appointment Types Distribution</h3>
            <div class="relative h-80">
                <canvas id="appointmentTypeChart"></canvas>
            </div>
        </div>
        <!-- Revenue Chart -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-nhd-blue mb-4">Revenue Trend (Last 6 Months)</h3>
            <div class="relative h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Appointment Status Chart -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-nhd-blue mb-4">Appointment Status Overview</h3>
            <div class="relative h-80">
                <canvas id="appointmentStatusChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Data Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Payment Statistics -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-nhd-blue mb-4">Payment Statistics</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Count</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Expected Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paymentStatistics as $stat): ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    <?php switch (strtolower($stat["Status"])) {
                                        case "paid":
                                            echo "bg-green-100 text-green-800";
                                            break;
                                        case "pending":
                                            echo "bg-yellow-100 text-yellow-800";
                                            break;
                                        case "overdue":
                                            echo "bg-red-100 text-red-800";
                                            break;
                                        default:
                                            echo "bg-gray-100 text-gray-800";
                                    } ?>">
                                    <?php echo ucfirst(
                                        $stat["Status"] ?: "Unknown"
                                    ); ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right"><?php echo number_format(
                                $stat["count"]
                            ); ?></td>
                            <td class="py-3 px-4 text-right">₱<?php echo number_format(
                                $stat["total_amount"],
                                2
                            ); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-nhd-blue mb-4">Payment Methods</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Method</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Count</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-700">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paymentMethods as $method): ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4"><?php echo htmlspecialchars(
                                $method["PaymentMethod"] ?: "Not Specified"
                            ); ?></td>
                            <td class="py-3 px-4 text-right"><?php echo number_format(
                                $method["count"]
                            ); ?></td>
                            <td class="py-3 px-4 text-right">₱<?php echo number_format(
                                $method["total_amount"],
                                2
                            ); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown Section -->
    <div id="revenue-breakdown-section" class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm mb-8">
        <h3 class="text-xl font-semibold text-nhd-blue mb-4">Revenue Breakdown - Contributing Appointments</h3>
        <p class="text-gray-600 mb-6">View the appointments that contributed to each revenue category. Click on the revenue cards above to filter by specific period.</p>
        
        <!-- Filter Tabs -->
        <div class="flex space-x-1 p-1 rounded-lg mb-6 w-fit">
            <button id="tab-total" class="revenue-tab glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl text-sm font-medium transition-colors active" onclick="showRevenueBreakdown('total')">
                All Time
            </button>
            <button id="tab-monthly" class="revenue-tab glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl text-sm font-medium transition-colors" onclick="showRevenueBreakdown('monthly')">
                This Month
            </button>
            <button id="tab-weekly" class="revenue-tab glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl text-sm font-medium transition-colors" onclick="showRevenueBreakdown('weekly')">
                This Week
            </button>
            <button id="tab-today" class="revenue-tab glass-card bg-nhd-blue/80 text-white px-4 py-2 rounded-2xl text-sm font-medium transition-colors" onclick="showRevenueBreakdown('today')">
                Today
            </button>
        </div>

        <!-- Revenue Tables Container -->
        <div id="revenue-tables-container">
            <!-- Total Revenue Table -->
            <div id="revenue-table-total" class="revenue-table-section">
                <div class="overflow-x-auto">
                    <table class="w-full sortable-appointment-table" data-section="revenue-total">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentID">
                                    Appointment ID
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DateTime">
                                    Date & Time
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientName">
                                    Patient Name
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorName">
                                    Doctor Name
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentType">
                                    Type
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="TotalAmount">
                                    Revenue
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table-body-revenue-total" class="bg-white divide-y divide-gray-200">
                            <?php foreach (
                                $revenueBreakdown["total"] as $index => $appointment
                            ): ?>
                            <tr class="hover:bg-nhd-blue/10 transition-colors duration-200 table-row" data-row-index="<?php echo $index; ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    #<?php echo str_pad(
                                        $appointment["AppointmentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($appointment["DateTime"])
                                    ); ?><br>
                                    <span class="text-xs text-gray-500"><?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientName"]
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars(
                                        $appointment["DoctorName"]
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    ₱<?php echo number_format(
                                        $appointment["TotalAmount"],
                                        2
                                    ); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Revenue Table -->
            <div id="revenue-table-monthly" class="revenue-table-section hidden">
                <div class="overflow-x-auto">
                    <table class="w-full sortable-appointment-table" data-section="revenue-monthly">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentID">
                                    Appointment ID
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DateTime">
                                    Date & Time
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientName">
                                    Patient Name
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorName">
                                    Doctor Name
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentType">
                                    Type
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="TotalAmount">
                                    Revenue
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table-body-revenue-monthly" class="bg-white divide-y divide-gray-200">
                            <?php foreach (
                                $revenueBreakdown["monthly"] as $index => $appointment
                            ): ?>
                            <tr class="hover:bg-nhd-blue/10 transition-colors duration-200 table-row" data-row-index="<?php echo $index; ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    #<?php echo str_pad(
                                        $appointment["AppointmentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($appointment["DateTime"])
                                    ); ?><br>
                                    <span class="text-xs text-gray-500"><?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientName"]
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars(
                                        $appointment["DoctorName"]
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    ₱<?php echo number_format(
                                        $appointment["TotalAmount"],
                                        2
                                    ); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Weekly Revenue Table -->
            <div id="revenue-table-weekly" class="revenue-table-section hidden">
                <div class="overflow-x-auto">
                    <table class="w-full sortable-appointment-table" data-section="revenue-weekly">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentID">
                                    Appointment ID
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DateTime">
                                    Date & Time
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientName">
                                    Patient Name
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorName">
                                    Doctor Name
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentType">
                                    Type
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="TotalAmount">
                                    Revenue
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table-body-revenue-weekly" class="bg-white divide-y divide-gray-200">
                            <?php foreach (
                                $revenueBreakdown["weekly"] as $index => $appointment
                            ): ?>
                            <tr class="hover:bg-nhd-blue/10 transition-colors duration-200 table-row" data-row-index="<?php echo $index; ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    #<?php echo str_pad(
                                        $appointment["AppointmentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($appointment["DateTime"])
                                    ); ?><br>
                                    <span class="text-xs text-gray-500"><?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientName"]
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars(
                                        $appointment["DoctorName"]
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    ₱<?php echo number_format(
                                        $appointment["TotalAmount"],
                                        2
                                    ); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Today Revenue Table -->
            <div id="revenue-table-today" class="revenue-table-section hidden">
                <div class="overflow-x-auto">
                    <table class="w-full sortable-appointment-table" data-section="revenue-today">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentID">
                                    Appointment ID
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DateTime">
                                    Date & Time
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="PatientName">
                                    Patient Name
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="DoctorName">
                                    Doctor Name
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="AppointmentType">
                                    Type
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                                <th class="sortable-header px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100/60 transition-colors" data-sort="TotalAmount">
                                    Revenue
                                    <span class="sort-indicator ml-1">
                                        <svg class="sort-icon-default inline-block w-3 h-3" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M6 1L8 4H4L6 1Z" fill="#9CA3AF"/>
                                            <path d="M6 11L4 8H8L6 11Z" fill="#9CA3AF"/>
                                        </svg>
                                        <span class="sort-icon-active hidden"></span>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table-body-revenue-today" class="bg-white divide-y divide-gray-200">
                            <?php foreach (
                                $revenueBreakdown["today"] as $index => $appointment
                            ): ?>
                            <tr class="hover:bg-nhd-blue/10 transition-colors duration-200 table-row" data-row-index="<?php echo $index; ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    #<?php echo str_pad(
                                        $appointment["AppointmentID"],
                                        6,
                                        "0",
                                        STR_PAD_LEFT
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo date(
                                        "M j, Y",
                                        strtotime($appointment["DateTime"])
                                    ); ?><br>
                                    <span class="text-xs text-gray-500"><?php echo date(
                                        "g:i A",
                                        strtotime($appointment["DateTime"])
                                    ); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars(
                                        $appointment["PatientName"]
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars(
                                        $appointment["DoctorName"]
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        <?php echo htmlspecialchars(
                                            $appointment["AppointmentType"]
                                        ); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    ₱<?php echo number_format(
                                        $appointment["TotalAmount"],
                                        2
                                    ); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="revenue-empty-state" class="hidden text-center py-8">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500">No revenue data available for this period</p>
        </div>
    </div>

        <!-- Monthly Appointments Trend -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm mb-8">
        <h3 class="text-xl font-semibold text-nhd-blue mb-4">Monthly Appointments Trend</h3>
        <div class="relative h-80">
            <canvas id="monthlyAppointmentsChart"></canvas>
        </div>
    </div>

        <!-- Doctor Performance Section -->
        <div class="glass-card border-gray-200 border-1 rounded-2xl p-6 shadow-sm mb-8">
        <h3 class="text-xl font-semibold text-nhd-blue mb-4">Doctor Performance</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Doctor</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Specialization</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Total Appointments</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Completed</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Revenue</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Success Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctorPerformance as $doctor): ?>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-nhd-blue rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                    <?php echo strtoupper(
                                        substr($doctor["doctor_name"], 0, 2)
                                    ); ?>
                                </div>
                                <?php echo htmlspecialchars(
                                    $doctor["doctor_name"]
                                ); ?>
                            </div>
                        </td>
                        <td class="py-3 px-4"><?php echo htmlspecialchars(
                            $doctor["Specialization"] ?: "General"
                        ); ?></td>
                        <td class="py-3 px-4 text-right"><?php echo number_format(
                            $doctor["total_appointments"]
                        ); ?></td>
                        <td class="py-3 px-4 text-right"><?php echo number_format(
                            $doctor["completed_appointments"]
                        ); ?></td>
                        <td class="py-3 px-4 text-right">₱<?php echo number_format(
                            $doctor["total_revenue"],
                            2
                        ); ?></td>
                        <td class="py-3 px-4 text-right">
                            <?php $successRate =
                                $doctor["total_appointments"] > 0
                                    ? ($doctor["completed_appointments"] /
                                            $doctor["total_appointments"]) *
                                        100
                                    : 0; ?>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo $successRate >= 80
                                    ? "bg-green-100 text-green-800"
                                    : ($successRate >= 60
                                        ? "bg-yellow-100 text-yellow-800"
                                        : "bg-red-100 text-red-800"); ?>">
                                <?php echo number_format($successRate, 1); ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
window.reportData = {
    revenue: <?php echo json_encode($revenueChart); ?>,
    appointmentTypes: <?php echo json_encode($appointmentTypeChart); ?>,
    appointmentStatus: <?php echo json_encode($appointmentStatusChart); ?>,
    monthlyAppointments: <?php echo json_encode($monthlyAppointments); ?>,
    dailyAppointments: <?php echo json_encode($dailyAppointments); ?>,
    revenueData: {
        total: <?php echo $summary["revenue"]["total"]; ?>,
        monthly: <?php echo $summary["revenue"]["monthly"]; ?>,
        weekly: <?php echo $summary["revenue"]["weekly"]; ?>,
        today: <?php echo $summary["revenue"]["today"]; ?>
    }
};

function getCurrentRevenueFilter() {
    const filter = document.getElementById('revenue-filter');
    return filter ? filter.value : 'total';
}

function updateRevenueDisplay() {
    const filter = getCurrentRevenueFilter();
    const amountElement = document.getElementById('revenue-amount');
    const periodElement = document.getElementById('revenue-period');
    
    if (amountElement && periodElement && window.reportData.revenueData) {
        const amount = window.reportData.revenueData[filter];
        const formattedAmount = '₱' + new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
        
        amountElement.textContent = formattedAmount;
        
        const periodTexts = {
            total: 'All time revenue',
            monthly: 'This month\'s revenue',
            weekly: 'This week\'s revenue',
            today: 'Today\'s revenue'
        };
        
        periodElement.textContent = periodTexts[filter];
    }
}

// Revenue breakdown functionality
function scrollToRevenueBreakdown(type) {
    const revenueSection = document.getElementById('revenue-breakdown-section');
    if (revenueSection) {
        revenueSection.scrollIntoView({ behavior: 'smooth' });
        // Show the correct tab after scrolling
        setTimeout(() => {
            showRevenueBreakdown(type);
        }, 300);
    }
}

function showRevenueBreakdown(type) {
    const tabs = document.querySelectorAll('.revenue-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active', 'bg-nhd-green/80');
        tab.classList.add('bg-nhd-blue/80');
    });
    
    const activeTab = document.getElementById(`tab-${type}`);
    if (activeTab) {
        activeTab.classList.add('active', 'bg-nhd-green/80', 'text-white');
        activeTab.classList.remove('text-gray-600');
    }

    const tableSections = document.querySelectorAll('.revenue-table-section');
    tableSections.forEach(section => {
        section.classList.add('hidden');
    });

    const targetTable = document.getElementById(`revenue-table-${type}`);
    const emptyState = document.getElementById('revenue-empty-state');
    
    if (targetTable) {
        const tableBody = targetTable.querySelector('tbody');
        const hasData = tableBody && tableBody.children.length > 0;
        
        if (hasData) {
            targetTable.classList.remove('hidden');
            emptyState.classList.add('hidden');
        } else {
            targetTable.classList.add('hidden');
            emptyState.classList.remove('hidden');
        }
    } else {
        emptyState.classList.remove('hidden');
    }
}


document.addEventListener('DOMContentLoaded', function() {
    const defaultTab = document.getElementById('tab-total');
    if (defaultTab) {
        defaultTab.classList.add('bg-nhd-green/80', 'text-white');
        defaultTab.classList.remove('text-gray-600');
    }
    
    const otherTabs = document.querySelectorAll('.revenue-tab:not(#tab-total)');
    otherTabs.forEach(tab => {
        // tab.classList.add('text-gray-600');
        // tab.classList.remove('bg-nhd-blue', 'text-white');
    });
});
</script>

<style>


#revenue-filter {
    background-color: rgba(255, 255, 255, 0.9);
    border: 1px solid #d1d5db;
    color: #1e40af;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

#revenue-filter:hover {
    background-color: rgba(255, 255, 255, 1);
    border-color: #9ca3af;
}

#revenue-filter:focus {
    outline: none;
    ring: 1px;
    ring-color: #1e40af;
    border-color: #1e40af;
    background-color: rgba(255, 255, 255, 1);
}

.sortable-header {
    user-select: none;
    position: relative;
}

.sortable-header:hover {
    background-color: rgba(229, 231, 235, 0.6) !important;
}

.sort-indicator {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 16px;
    height: 16px;
    transition: all 0.2s ease;
}

.sort-icon-default {
    opacity: 0.5;
    transition: opacity 0.2s ease;
}

.sortable-header:hover .sort-icon-default {
    opacity: 0.8;
}

.sort-icon-active {
    font-size: 12px;
    font-weight: bold;
    color: #374151;
}

.sort-icon-active.asc::before {
    content: '▲';
    color: #059669;
}

.sort-icon-active.desc::before {
    content: '▼';
    color: #DC2626;
}

.sortable-header.sorting .sort-icon-default {
    display: none;
}

.sortable-header.sorting .sort-icon-active {
    display: inline-block;
}
</style>
