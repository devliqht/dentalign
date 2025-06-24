<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle)
        ? htmlspecialchars($pageTitle) . " - DentAlign"
        : "DentAlign"; ?></title>
    
    <link href="<?php echo BASE_URL; ?>/app/styles/global.css" rel="stylesheet"/>
    <link href="<?php echo BASE_URL; ?>/app/styles/output.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/app/styles/components/Toast.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/app/styles/components/Header.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/public/logo.png"/>
    
    
    <!-- Additional heads -->
    <?php if (isset($additionalHead)): ?>
        <?php echo $additionalHead; ?>
    <?php endif; ?>
    
    <style>
        :root {
            --sidebar-width: 16rem;
            --gap-size: 1rem;
        }
        
        .app-layout {
            min-height: 100vh;
        }
        
        .app-layout.no-sidebar {
            padding: 0;
        }
        
        .sidebar-container {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            z-index: 20;
            padding: var(--gap-size);
        }
        
        .main-content-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            gap: var(--gap-size);
            padding: var(--gap-size);
            <?php if (
                isset($pageTitle) &&
                ($pageTitle === "Login" ||
                    $pageTitle === "Sign Up" ||
                    $pageTitle === "404")
            ): ?>
            <?php else: ?>
            padding-top: 4rem;
            <?php endif; ?>
            position: relative;
            box-sizing: border-box;
        }
        
        .main-content-wrapper.no-sidebar {
            margin-left: 0;
            width: 100%;
            min-height: 100vh;
            <?php if (
                isset($pageTitle) &&
                ($pageTitle === "Login" ||
                    $pageTitle === "Sign Up" ||
                    $pageTitle === "404")
            ): ?>
            <?php else: ?>
            padding-top: 4rem;
            <?php endif; ?>
        }
        
        @media (max-width: 1024px) {
            .sidebar-container {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                z-index: 50;
            }
            
            .sidebar-container.sidebar-open {
                transform: translateX(0);
            }
            
            .main-content-wrapper {
                margin-left: 0;
                width: 100%;
                padding: var(--gap-size);
                padding-top: 4rem;
            }
        }
        
        .content-section {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        

    </style>
</head>
<body class="<?php echo isset($bodyClass)
    ? htmlspecialchars($bodyClass)
    : ""; ?>" style="background: url('<?php echo BASE_URL; ?>/public/low.svg'); background-size: cover; background-attachment: fixed;">
    
    <div class="app-layout <?php echo isset($_SESSION["user_name"])
        ? "has-sidebar"
        : "no-sidebar"; ?>">
        <?php include __DIR__ . "/components/Toast.php"; ?>
        
        <!-- SIDEBAR -->
        <?php if (isset($_SESSION["user_name"])): ?>
            <div class="sidebar-container" id="sidebar-container">
                <div class="glass-card h-full">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center">
                            <img src="<?php echo BASE_URL; ?>/public/logo.png" alt="North Hill Dental Logo" class="w-10 h-10 rounded-full mr-3" />
                            <h2 class="text-lg font-family-bodoni font-bold tracking-tight text-nhd-blue">North Hill Dental</h2>
                        </div>
                        <button id="sidebar-close" class="lg:hidden p-1 rounded-md hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- User info -->
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-nhd-blue rounded-full flex items-center justify-center text-white font-semibold">
                                <?php echo strtoupper(
                                    substr($_SESSION["user_name"], 0, 1)
                                ); ?>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars(
                                    $_SESSION["user_name"]
                                ); ?></p>
                                <p class="text-xs text-gray-500"><?php
                                $displayType = $_SESSION["user_type"] ?? "User";
                                if ($displayType === "ClinicStaff") {
                                    $displayType = "Doctor";
                                }
                                echo htmlspecialchars($displayType);
                                ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="p-2">
                        <?php
                        $userType = $_SESSION["user_type"] ?? "";
                        $currentPath = parse_url(
                            $_SERVER["REQUEST_URI"],
                            PHP_URL_PATH
                        );
                        $currentPath = str_replace(
                            "/dentalign",
                            "",
                            $currentPath
                        );

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
                                    "url" => "/patient/results",
                                    "icon" => "document-text",
                                    "label" => "Results",
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
                                    ? "bg-nhd-blue/85 glass-card text-white shadow-xl"
                                    : "text-gray-700 hover:bg-nhd-blue/20";
                                ?>
                                <a href="<?php echo BASE_URL .
                                    $item["url"]; ?>" 
                                   class="group flex items-center p-4 text-sm font-medium rounded-3xl transition-all duration-200 <?php echo $activeClass; ?>">
                                    <svg class="<?php echo $isActive
                                        ? "text-nhd-pale"
                                        : "text-gray-600 group-hover:text-gray-500"; ?> mr-3 flex-shrink-0 h-5 w-5" 
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <?php echo getSidebarIcon(
                                            $item["icon"]
                                        ); ?>
                                    </svg>
                                    <?php echo htmlspecialchars(
                                        $item["label"]
                                    ); ?>
                                </a>
                            <?php
                            endforeach; ?>
                        </div>


                    </nav>
                </div>
                
                <!-- Mobile Overlay -->
                <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
            </div>
        <?php endif; ?>

        <!-- MAIN CONTENT AREA -->
        <div class="main-content-wrapper <?php echo !isset(
            $_SESSION["user_name"]
        )
            ? "no-sidebar"
            : ""; ?>">
            
            <!-- Glassmorphism Header Component -->
            <?php include __DIR__ . "/components/Header.php"; ?>
            
            <!-- HEADER -->
            <?php if (!isset($hideHeader) || !$hideHeader): ?>
                <?php if (isset($_SESSION["user_name"])): ?>
                    <header class="content-section">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <!-- Mobile menu button -->
                                <button id="sidebar-toggle" class="lg:hidden p-2 rounded-md hover:bg-gray-100 transition-colors mr-3">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                </button>
                                <h1 class="text-xl font-bold text-nhd-blue font-family-bodoni">North Hill Dental</h1>
                            </div>
                            <div class="flex items-center space-x-4">
                            </div>
                        </div>
                    </header>
                <?php endif; ?>
            <?php endif; ?>

            <!-- NAV -->
            <?php if (isset($navigation)): ?>
                <nav class="content-section">
                    <?php echo $navigation; ?>
                </nav>
            <?php endif; ?>

            <!-- Main Content -->
            <main class="<?php echo isset($mainClass)
                ? htmlspecialchars($mainClass)
                : "flex-1"; ?>">
                <?php echo $content; ?>
            </main>

            <?php include __DIR__ . "/components/Footer.php"; ?>
           
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>/app/views/scripts/Toast.js"></script>

    <!-- Additional Scripts -->
    <?php if (isset($additionalScripts)): ?>
        <?php echo $additionalScripts; ?>
    <?php endif; ?>

    <!-- Sidebar JavaScript -->
    <?php if (isset($_SESSION["user_name"])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarContainer = document.getElementById('sidebar-container');
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const sidebarClose = document.getElementById('sidebar-close');
                const sidebarOverlay = document.getElementById('sidebar-overlay');

                function toggleSidebar() {
                    sidebarContainer.classList.toggle('sidebar-open');
                    sidebarOverlay.classList.toggle('hidden');
                }

                function closeSidebar() {
                    sidebarContainer.classList.remove('sidebar-open');
                    sidebarOverlay.classList.add('hidden');
                }

                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', toggleSidebar);
                }
                
                if (sidebarClose) {
                    sidebarClose.addEventListener('click', closeSidebar);
                }
                
                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', closeSidebar);
                }

                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 1024) {
                        closeSidebar();
                    }
                });
            });
        </script>
    <?php endif; ?>
</body>
</html> 