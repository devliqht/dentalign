<div class="relative min-h-screen flex flex-col items-center justify-center">
    <div class="flex flex-col space-y-4 index-form">
        <img src="<?php echo BASE_URL; ?>/public/logo.png" alt="DentAlign Logo" class="mx-auto w-[60%] rounded-full mb-2" />
        
        <div class="flex flex-col items-center justify-center gap-2">
                <h2 class="text-4xl text-center tracking-tight font-family-bodoni font-semibold text-nhd-blue">Welcome</h2>
                <p>Don't have an account? <a class="underline text-nhd-green" href="<?php echo BASE_URL; ?>/signup">Sign up here</a></p>
        </div>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/login" class="space-y-2">
                <div class="flex flex-col">
                    <input type="email" id="email" name="email" placeholder="Email" required />
                </div>
                
                <div class="flex flex-col">
                    <input type="password" id="password" name="password" placeholder="Password" required />
                </div>
                
                <button type="submit">Login</button>
            </form>

    </div>
</div> 