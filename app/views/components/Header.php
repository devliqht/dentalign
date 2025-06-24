<?php if (isset($_SESSION["user_name"])): ?>
<div class="header-component">
    <div class="glass-card p-4 shadow-md">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <!-- Profile Button -->
                <a href="<?php echo BASE_URL; ?>/<?php echo ($_SESSION[
    "user_type"
] ??
    "") ===
"Patient"
    ? "patient"
    : "staff"; ?>/profile" 
                   class="header-action-btn" 
                   title="Profile">
                   <div class="w-6 h-6 bg-nhd-blue/80 rounded-full flex items-center justify-center text-nhd-pale text-xs font-semibold">
                    <?php echo strtoupper(
                        substr($_SESSION["user_name"], 0, 1)
                    ); ?>
                </div>
                    <span class="hidden sm:inline ml-2">Profile</span>
                </a>

                <!-- FAQ Button -->
                <a href="<?php echo BASE_URL; ?>/faq" 
                   class="header-action-btn"
                   title="FAQ">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </a>

                <!-- Logout Button -->
                <a href="<?php echo BASE_URL; ?>/logout" 
                   class="header-logout-btn"
                   title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
