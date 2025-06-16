<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - DentAlign</title>
</head>
<body>
    <div class="header">
        <h1>DentAlign</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
            <a href="<?php echo BASE_URL; ?>/logout" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome-card">
            <h2>Welcome to DentAlign</h2>
            <p>You have successfully logged into your account. This is your dashboard where you can manage your dental care activities.</p>
        </div>

        <div class="user-details">
            <h3>Your Account Information</h3>
            
            <div class="detail-row">
                <div class="detail-label">Name:</div>
                <div class="detail-value"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Email:</div>
                <div class="detail-value"><?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Account Type:</div>
                <div class="detail-value">
                    <span class="user-type-badge badge-<?php echo strtolower(str_replace(' ', '', $_SESSION['user_type'])); ?>">
                        <?php echo htmlspecialchars($_SESSION['user_type']); ?>
                    </span>
                </div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">User ID:</div>
                <div class="detail-value"><?php echo htmlspecialchars($_SESSION['user_id']); ?></div>
            </div>

            <?php if ($_SESSION['user_type'] === 'Patient'): ?>
                <hr style="margin: 2rem 0;">
                <h4>Patient Features</h4>
                <p>As a patient, you can schedule appointments, view your treatment history, and manage your dental records.</p>
            <?php elseif ($_SESSION['user_type'] === 'ClinicStaff'): ?>
                <hr style="margin: 2rem 0;">
                <h4>Staff Features</h4>
                <p>As clinic staff, you have access to patient management tools, appointment scheduling, and administrative functions.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
