<div class="bg-[#ecf0f1] relative min-h-screen flex flex-col items-center justify-center">
    <div class="flex flex-col">
        <h2 class="text-3xl ">DentAlign</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/login">
                <div class="flex flex-col">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="flex flex-col">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit">Login</button>
            </form>
            
            <div class="links">
                <p>Don't have an account? <a href="<?php echo BASE_URL; ?>/signup">Sign up here</a></p>
            </div>
    </div>
</div> 