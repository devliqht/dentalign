<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | DentAlign</title>
    <style>

    </style>
</head>
<body>
    <div class="container">
        <h1 class="error-title">Page Not Found</h1>
        
        <p class="error-message">
            Oops! The page you're looking for doesn't exist. It might have been moved, deleted, or you entered the wrong URL.
        </p>
        
        <div class="redirect-info">
            <p>You will be automatically redirected to the login page in:</p>
            <div class="countdown" id="countdown">5</div>
            <p>seconds</p>
        </div>
        
        <div class="button-group">
            <a href="<?php echo BASE_URL; ?>/login" class="btn btn-primary">Go to Login</a>
            <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
        </div>
    </div>

    <script>
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = '<?php echo BASE_URL; ?>/login';
            }
        }, 1000);
    </script>
</body>
</html>
