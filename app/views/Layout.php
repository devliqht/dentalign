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
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/public/logo.png"/>
    
    <!-- Additional content -->
    <?php if (isset($additionalHead)): ?>
        <?php echo $additionalHead; ?>
    <?php endif; ?>
    
    <!-- Better Layout CSS -->
    <style>
        :root {
            --sidebar-width: 16rem; /* 256px equivalent to w-64 */
            --gap-size: 1rem;
        }
        
        .app-layout {
            display: grid;
            min-height: 100vh;
            gap: var(--gap-size);
            padding: var(--gap-size);
        }
        
        .app-layout.has-sidebar {
            grid-template-columns: var(--sidebar-width) 1fr;
        }
        
        .app-layout.no-sidebar {
            grid-template-columns: 1fr;
            padding: 0;
        }
        
        .sidebar-container {
            position: relative;
        }
        
        .main-content-wrapper {
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - 2rem);
            gap: var(--gap-size);
            padding-top: 2rem;
        }
        
        .main-content-wrapper.no-sidebar {
            min-height: 100vh;
        }
        
        /* Mobile responsive behavior */
        @media (max-width: 1024px) {
            .app-layout.has-sidebar {
                grid-template-columns: 1fr;
                padding: 0;
            }
            
            .sidebar-container {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 50;
                width: var(--sidebar-width);
                height: 100vh;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                padding: var(--gap-size);
            }
            
            .sidebar-container.sidebar-open {
                transform: translateX(0);
            }
            
            .main-content-wrapper {
                min-height: 100vh;
                padding: 0;
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
    : ""; ?>" style="background: url('<?php echo BASE_URL; ?>/public/stacked-waves-haikei.svg'); background-size: cover; background-attachment: fixed;">
    
    <div class="app-layout <?php echo isset($_SESSION["user_name"])
        ? "has-sidebar"
        : "no-sidebar"; ?>">
        
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

                        <div class="border-t border-gray-200 my-4"></div>

                        <div class="space-y-1">
                            <a href="<?php echo BASE_URL; ?>/profile" 
                               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-5 w-5" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php echo getSidebarIcon("user"); ?>
                                </svg>
                                Profile
                            </a>
                            
                            <a href="<?php echo BASE_URL; ?>/faq" 
                               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="text-gray-400 group-hover:text-gray-500 mr-3 flex-shrink-0 h-5 w-5" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php echo getSidebarIcon(
                                        "question-mark-circle"
                                    ); ?>
                                </svg>
                                FAQ
                            </a>
                            
                            <a href="<?php echo BASE_URL; ?>/logout" 
                               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="text-red-500 mr-3 flex-shrink-0 h-5 w-5" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php echo getSidebarIcon("logout"); ?>
                                </svg>
                                Logout
                            </a>
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
                                <span class="text-sm text-gray-700">Welcome, <?php echo htmlspecialchars(
                                    $_SESSION["user_name"]
                                ); ?>!</span>
                                <a href="<?php echo BASE_URL; ?>/logout" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                    Logout
                                </a>
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

            <!-- FOOTER -->
            <?php if (!isset($hideFooter) || !$hideFooter): ?>
                <footer class="glass-card bg-nhd-blue/85 text-white rounded-2xl">
                    <div class="max-w-full mx-auto px-6 py-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            
                            <div class="lg:col-span-2">
                                <div class="flex items-center mb-4">
                                    <img src="<?php echo BASE_URL; ?>/public/logo.png" alt="North Hill Dental Logo" class="w-12 h-12 rounded-full mr-3" />
                                    <h3 class="text-xl font-family-bodoni font-semibold">North Hill Dental</h3>
                                </div>
                                <p class="text-gray-300 mb-4 leading-relaxed">
                                    Your trusted partner in dental health and beautiful smiles. 
                                    We provide comprehensive dental care in a comfortable, modern environment.
                                </p>
                                <div class="flex space-x-4">
                                    <a href="#" class="text-gray-300 hover:text-nhd-green transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-300 hover:text-nhd-green transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-300 hover:text-nhd-green transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378 0 0-.599 2.282-.744 2.840-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24.001 12.017 24.001c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-lg font-family-bodoni font-semibold mb-4">Quick Links</h4>
                                <ul class="space-y-2">
                                    <li><a href="<?php echo BASE_URL; ?>/" class="text-gray-300 hover:text-nhd-green transition-colors">Home</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/about" class="text-gray-300 hover:text-nhd-green transition-colors">About Us</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/services" class="text-gray-300 hover:text-nhd-green transition-colors">Services</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/appointment" class="text-gray-300 hover:text-nhd-green transition-colors">Book Appointment</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/contact" class="text-gray-300 hover:text-nhd-green transition-colors">Contact</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>/emergency" class="text-gray-300 hover:text-nhd-green transition-colors">Emergency Care</a></li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="text-lg font-family-bodoni font-semibold mb-4">Contact Info</h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-nhd-green mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div class="text-gray-300 text-sm">
                                            <p>123 Sitio Nasipit, Barangay Banilad</p>
                                            <p>Cebu City, Cebu</p>
                                            <p>T2N 1T4</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-nhd-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <a href="tel:09275086540" class="text-gray-300 hover:text-nhd-green transition-colors text-sm">0927 508 6540</a>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-nhd-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <a href="mailto:info@northhilldental.ph" class="text-gray-300 hover:text-nhd-green transition-colors text-sm">info@northhilldental.ph</a>
                                    </div>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-nhd-green mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-gray-300 text-sm">
                                            <p>Monday - Friday</p>
                                            <p>8:00 AM - 6:00 PM</p>
                                            <p class="text-nhd-green font-medium mt-1">Emergency: 24/7</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-nhd-blue/20 py-6 mt-8">
                            <div class="flex flex-col md:flex-row justify-between items-center">
                                <p class="text-gray-300 text-sm">
                                    © <?php echo date(
                                        "Y"
                                    ); ?> North Hill Dental. All rights reserved.
                                </p>
                                <div class="flex space-x-6 mt-4 md:mt-0">
                                    <a href="<?php echo BASE_URL; ?>/privacy" class="text-gray-300 hover:text-nhd-green text-sm transition-colors">Privacy Policy</a>
                                    <a href="<?php echo BASE_URL; ?>/terms" class="text-gray-300 hover:text-nhd-green text-sm transition-colors">Terms of Service</a>
                                    <a href="<?php echo BASE_URL; ?>/accessibility" class="text-gray-300 hover:text-nhd-green text-sm transition-colors">Accessibility</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            <?php endif; ?>
        </div>
    </div>

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

                // Handle window resize
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