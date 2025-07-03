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
            z-index: 40;
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
            padding: 0;
            <?php else: ?>
            padding-top: 6rem;
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
                z-index: 45;
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
    : ""; ?>" >
    
    <div class="app-layout <?php echo isset($_SESSION["user_name"])
        ? "has-sidebar"
        : "no-sidebar"; ?>">
        <?php include __DIR__ . "/components/Toast.php"; ?>
        <?php include __DIR__ . "/components/Header.php"; ?>

        <?php if (!$hideSidebar): ?>
        <?php include __DIR__ . "/components/Sidebar.php"; ?>     
        <?php endif; ?>
        
        <!-- MAIN CONTENT AREA -->
        <div class="main-content-wrapper <?php echo !isset(
            $_SESSION["user_name"]
        )
            ? "no-sidebar"
            : ""; ?>">
            
            <?php if (isset($_SESSION["user_name"])): ?>
                <button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-3 glass-card rounded-full shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out">
                    <svg id="hamburger-icon" class="w-5 h-5 text-nhd-blue transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="close-icon" class="w-5 h-5 text-nhd-blue absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-0 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
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

    <script>
        window.BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    
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
                const sidebarOverlay = document.getElementById('sidebar-overlay');
                const hamburgerIcon = document.getElementById('hamburger-icon');
                const closeIcon = document.getElementById('close-icon');

                function toggleSidebar() {
                    const isOpening = !sidebarContainer.classList.contains('sidebar-open');
                    
                    sidebarContainer.classList.toggle('sidebar-open');
                    sidebarOverlay.classList.toggle('hidden');
                    
                    if (isOpening) {
                        // Move button to sidebar position and show close icon
                        sidebarToggle.style.transform = 'translateX(calc(var(--sidebar-width) - 4rem))';
                        setTimeout(() => {
                            hamburgerIcon.style.opacity = '0';
                            closeIcon.style.opacity = '1';
                        }, 150);
                    } else {
                        // Move button back to original position and show hamburger icon
                        hamburgerIcon.style.opacity = '1';
                        closeIcon.style.opacity = '0';
                        setTimeout(() => {
                            sidebarToggle.style.transform = 'translateX(0)';
                        }, 150);
                    }
                }

                function closeSidebar() {
                    sidebarContainer.classList.remove('sidebar-open');
                    sidebarOverlay.classList.add('hidden');
                    
                    // Reset button position and icon
                    hamburgerIcon.style.opacity = '1';
                    closeIcon.style.opacity = '0';
                    setTimeout(() => {
                        sidebarToggle.style.transform = 'translateX(0)';
                    }, 150);
                }

                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', toggleSidebar);
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