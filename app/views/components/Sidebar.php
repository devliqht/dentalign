<div class="sidebar-container " id="sidebar-container">
    <div class="h-full border-r-1 border-gray-200 bg-white shadow-sm">
        <div class="flex items-center p-6 pb-4 border-b-1 border-gray-200">
            <img src="<?php echo BASE_URL; ?>/public/logo.png" alt="North Hill Dental Logo" class="w-10 h-10 rounded-full mr-1" />
            <h2 class="text-lg font-family-bodoni font-bold tracking-tight text-nhd-blue">North Hill Dental</h2>
        </div>

        <!-- Navigation -->
        <nav class="p-2">
            <?php
            $userType = $_SESSION["user_type"] ?? "";
            $currentPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
            $currentPath = str_replace("/dentalign", "", $currentPath);

            $navItems = [];

            if ($userType === "Patient") {
                $navItems = [
                    [
                        "url" => "/patient/dashboard",
                        "icon" => "home",
                        "label" => "Dashboard",
                    ],
                    [
                        "url" => "/patient/bookings",
                        "icon" => "calendar",
                        "label" => "My Bookings",
                    ],
                    [
                        "url" => "/patient/book-appointment",
                        "icon" => "plus-circle",
                        "label" => "Book Appointment",
                    ],
                    [
                        "url" => "/patient/payments",
                        "icon" => "credit-card",
                        "label" => "Payments",
                    ],
                    [
                        "url" => "/patient/dental-chart",
                        "icon" => "document-text",
                        "label" => "Dental Chart",
                    ],
                    [
                        "url" => "/patient/profile",
                        "icon" => "user",
                        "label" => "Profile",
                    ],
                ];
            } elseif ($userType === "ClinicStaff") {
                $navItems = [
                    [
                        "url" => "/doctor/dashboard",
                        "icon" => "home",
                        "label" => "Dashboard",
                    ],
                    [
                        "url" => "/doctor/schedule",
                        "icon" => "calendar",
                        "label" => "Schedule",
                    ],
                    [
                        "url" => "/doctor/appointment-history",
                        "icon" => "clock",
                        "label" => "Appointment History",
                    ],
                    [
                        "url" => "/doctor/patient-records",
                        "icon" => "users",
                        "label" => "Patient Records",
                    ],
                    [
                        "url" => "/staff/profile",
                        "icon" => "user",
                        "label" => "Profile",
                    ],
                ];
            }

            function getSidebarIcon($iconName)
            {
                $icons = [
                    "home" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
                    "calendar" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
                    "plus-circle" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>',
                    "credit-card" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
                    "document-text" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                    "clock" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    "users" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>',
                    "user" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
                    "question-mark-circle" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    "logout" =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>',
                ];
                return $icons[$iconName] ?? $icons["home"];
            }
            ?>

            <div class="space-y-1">
                <?php foreach ($navItems as $item):

                    $isActive = $currentPath === $item["url"];
                    $activeClass = $isActive
                        ? "bg-nhd-blue/85 glass-card text-white shadow-xl rounded-xl"
                        : "text-gray-700 hover:bg-nhd-blue/20";
                    ?>
                    
                    <a href="<?php echo BASE_URL . $item["url"]; ?>" 
                        class="group flex items-center p-4 text-sm font-medium rounded-xl transition-all duration-200 <?php echo $activeClass; ?>">
                        <svg class="<?php echo $isActive
                            ? "text-nhd-pale"
                            : "text-gray-600 group-hover:text-gray-500"; ?> mr-3 flex-shrink-0 h-5 w-5" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <?php echo getSidebarIcon($item["icon"]); ?>
                        </svg>
                        <?php echo htmlspecialchars($item["label"]); ?>
                    </a>
                <?php
                endforeach; ?>
            </div>
        </nav>
    </div>
</div>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden"></div>