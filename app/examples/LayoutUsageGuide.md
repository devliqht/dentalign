# DentAlign Layout System

This layout system provides a global template similar to Next.js layouts, allowing you to:
- Include global CSS on every page automatically
- Share common header/footer across pages
- Customize page titles and meta information
- Add page-specific styles and scripts
- Control layout components per page

## Files Structure

```
app/
├── views/
│   ├── Layout.php              # Main layout template
│   └── pages/                  # Content-only view files
│       ├── LoginContent.php    # Login page content
│       ├── HomeContent.php     # Home page content
│       └── ...
├── helpers/
│   └── LayoutHelper.php        # Helper class for rendering
└── examples/
    └── ExampleController.php   # Usage examples
```

## Basic Usage

### 1. Simple Page Rendering

```php
<?php
require_once 'app/helpers/LayoutHelper.php';

// Render a page with default layout
LayoutHelper::render('pages/HomeContent');
```

### 2. Page with Custom Title

```php
$layoutConfig = [
    'title' => 'Dashboard'
];

LayoutHelper::render('pages/HomeContent', [], $layoutConfig);
```

### 3. Passing Data to View

```php
$data = [
    'user' => $userInfo,
    'posts' => $posts
];

$layoutConfig = [
    'title' => 'User Profile'
];

LayoutHelper::render('pages/ProfileContent', $data, $layoutConfig);
```

## Layout Configuration Options

```php
$layoutConfig = [
    // Page title (will show as "Title - DentAlign")
    'title' => 'Page Title',
    
    // Additional CSS classes for body tag
    'bodyClass' => 'bg-gray-50 custom-class',
    
    // CSS classes for main content area
    'mainClass' => 'min-h-screen bg-white',
    
    // Hide header (useful for login/landing pages)
    'hideHeader' => true,
    
    // Hide footer
    'hideFooter' => true,
    
    // Custom navigation HTML
    'navigation' => '<nav>...</nav>',
    
    // Additional CSS/meta tags for <head>
    'additionalHead' => '<link rel="stylesheet" href="custom.css">',
    
    // Additional JavaScript before </body>
    'additionalScripts' => '<script src="custom.js"></script>'
];
```

## Common Use Cases

### Login/Registration Pages (No Header/Footer)
```php
$layoutConfig = [
    'title' => 'Login',
    'hideHeader' => true,
    'hideFooter' => true,
    'bodyClass' => 'bg-gray-50',
    'mainClass' => 'min-h-screen flex items-center justify-center'
];

LayoutHelper::render('pages/LoginContent', $data, $layoutConfig);
```

### Dashboard Pages (With Navigation)
```php
$navigation = '
    <div class="py-3">
        <div class="flex space-x-8">
            <a href="/dashboard" class="text-blue-600">Dashboard</a>
            <a href="/patients" class="text-gray-900">Patients</a>
            <a href="/appointments" class="text-gray-900">Appointments</a>
        </div>
    </div>
';

$layoutConfig = [
    'title' => 'Dashboard',
    'navigation' => $navigation,
    'mainClass' => 'min-h-screen bg-gray-50'
];

LayoutHelper::render('pages/DashboardContent', $data, $layoutConfig);
```

### Pages with Custom CSS/JS
```php
$additionalHead = '
    <link href="https://cdn.jsdelivr.net/npm/chart.js" rel="stylesheet">
    <style>
        .custom-chart { height: 400px; }
    </style>
';

$additionalScripts = '
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize charts
        new Chart(document.getElementById("myChart"), config);
    </script>
';

$layoutConfig = [
    'title' => 'Analytics',
    'additionalHead' => $additionalHead,
    'additionalScripts' => $additionalScripts
];

LayoutHelper::render('pages/AnalyticsContent', $data, $layoutConfig);
```

### AJAX/Partial Rendering (No Layout)
```php
// For AJAX responses or partial content
LayoutHelper::renderPartial('partials/UserList', $data);
```

## Creating New Views

1. Create content-only PHP files in `app/views/pages/`
2. Don't include `<html>`, `<head>`, `<body>` tags
3. Focus only on the main content
4. Use the layout system to render them

Example content file (`app/views/pages/NewPageContent.php`):
```php
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4"><?php echo $pageTitle; ?></h1>
    <p><?php echo $content; ?></p>
</div>
```

## Global CSS

The layout automatically includes:
- `app/styles/global.css` (Tailwind CSS with custom styles)
- `app/styles/output.css` (Compiled CSS)

These files are loaded on every page, so you don't need to include them manually.

## Benefits

✅ **DRY Principle**: No repeated HTML structure
✅ **Global Styles**: CSS automatically included everywhere
✅ **Consistent Header/Footer**: Shared across all pages
✅ **Flexible**: Easy to customize per page
✅ **Maintainable**: Change layout once, affects all pages
✅ **SEO Friendly**: Proper title and meta tag management 