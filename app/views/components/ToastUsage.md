# Toast Notification System

A reusable toast notification component with progress bars, auto-dismiss, and manual close functionality.

## Files Structure

```
app/
├── views/
│   ├── components/
│   │   ├── Toast.php          # HTML template
│   │   └── ToastUsage.md      # This documentation
│   └── scripts/
│       └── Toast.js           # JavaScript functionality
└── styles/
    └── components/
        └── Toast.css          # CSS styling
```

## Basic Usage

### 1. Include the Component

In your PHP view file:

```php
<!-- Include Toast Component -->
<?php include __DIR__ . '/../../components/Toast.php'; ?>

<!-- Include CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/app/styles/components/Toast.css">

<!-- Include JavaScript -->
<script src="<?php echo BASE_URL; ?>/app/views/scripts/Toast.js"></script>
```

### 2. Show Toasts via JavaScript

```javascript
// Success toast (green, 5 seconds)
toast.success("Appointment booked successfully!");

// Error toast (red, 8 seconds)
toast.error("Failed to book appointment.");

// Info toast (blue, 5 seconds)
toast.info("Please check your email for confirmation.");

// Warning toast (yellow, 6 seconds)
toast.warning("Your session will expire in 5 minutes.");

// Custom duration
toast.success("Custom message", 3000); // 3 seconds
```

### 3. Server-Side Messages

For server-side redirects with messages:

```php
// In your controller
$_SESSION['success'] = "Appointment booked successfully!";
$_SESSION['error'] = "Failed to book appointment.";

// In your view, add this script before including Toast.js
<script>
window.serverMessages = {
    <?php if (isset($_SESSION['success'])): ?>
        success: <?php echo json_encode($_SESSION['success']); ?>,
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        error: <?php echo json_encode($_SESSION['error']); ?>,
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
};
</script>
```

## Advanced Usage

### Manual Control

```javascript
// Close specific toast
const myToast = toast.success("Hello!");
toast.close(myToast.element);

// Close all toasts
toast.closeAll();

// Check if system is ready
if (window.toast) {
    toast.success("System ready!");
}
```

### Configuration

```javascript
// Maximum toasts shown simultaneously
toast.maxToasts = 5; // default: 3

// Custom duration for specific types
toast.success("Message", 10000); // 10 seconds
```

## CSS Customization

Override styles by targeting toast classes:

```css
/* Custom toast background */
.toast-success {
    background: rgba(your-custom-color, 0.95);
}

/* Custom progress bar */
.toast-success .toast-progress-fill {
    background: linear-gradient(90deg, #custom1, #custom2);
}

/* Custom animation timing */
.toast-notification {
    transition-duration: 500ms;
}
```