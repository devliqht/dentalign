<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - DentAlign' : 'DentAlign'; ?></title>
    
    <link href="<?php echo BASE_URL; ?>/app/styles/global.css" rel="stylesheet"/>
    <link href="<?php echo BASE_URL; ?>/app/styles/output.css" rel="stylesheet"/>
    
    <!-- Additional content -->
    <?php if (isset($additionalHead)): ?>
        <?php echo $additionalHead; ?>
    <?php endif; ?>
</head>
<body class="<?php echo isset($bodyClass) ? htmlspecialchars($bodyClass) : ''; ?>">
    
    <!-- HEADER -->
    <?php if (!isset($hideHeader) || !$hideHeader): ?>
        <?php if (isset($_SESSION['user_name'])): ?>
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <div class="flex items-center">
                            <h1 class="text-xl font-semibold text-gray-900">DentAlign</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                            <a href="<?php echo BASE_URL; ?>/logout" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>
        <?php endif; ?>
    <?php endif; ?>

    <!-- NAV -->
    <?php if (isset($navigation)): ?>
        <nav class="bg-gray-50 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <?php echo $navigation; ?>
            </div>
        </nav>
    <?php endif; ?>



    <!-- Main Content Area -->
    <main class="<?php echo isset($mainClass) ? htmlspecialchars($mainClass) : 'min-h-screen'; ?>">
        <?php echo $content; ?>
    </main>




    <!-- FOOTER -->
    <?php if (!isset($hideFooter) || !$hideFooter): ?>
        <footer class="bg-gray-50 border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    © <?php echo date('Y'); ?> DentAlign. All rights reserved.
                </p>
            </div>
        </footer>
    <?php endif; ?>

    <!-- ADDT. SCRIPTS -->
    <?php if (isset($additionalScripts)): ?>
        <?php echo $additionalScripts; ?>
    <?php endif; ?>
</body>
</html> 