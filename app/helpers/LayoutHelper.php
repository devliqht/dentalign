<?php
class LayoutHelper {
    /**
     * Render a view with the global layout 
     * 
     * @param string $viewFile - Path to the view file (without .php extension)
     * @param array $data - Data to pass to the view
     * @param array $layoutConfig - Configuration for the layout
     * 
     * 
     */
    public static function render($viewFile, $data = [], $layoutConfig = []) {
        extract($data);
        
        $pageTitle = $layoutConfig['title'] ?? null;
        $bodyClass = $layoutConfig['bodyClass'] ?? '';
        $mainClass = $layoutConfig['mainClass'] ?? 'min-h-screen';
        $hideHeader = $layoutConfig['hideHeader'] ?? false;
        $hideFooter = $layoutConfig['hideFooter'] ?? false;
        $navigation = $layoutConfig['navigation'] ?? null;
        $additionalHead = $layoutConfig['additionalHead'] ?? null;
        $additionalScripts = $layoutConfig['additionalScripts'] ?? null;
        
        ob_start();
        
        $viewPath = __DIR__ . '/../views/' . $viewFile . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("View file not found: " . $viewPath);
        }
        
        $content = ob_get_clean();
        
        include __DIR__ . '/../views/Layout.php';
    }
    
    /**
     * Render a view without layout (for AJAX requests, etc.)
     */
    public static function renderPartial($viewFile, $data = []) {
        extract($data);
        
        $viewPath = __DIR__ . '/../views/' . $viewFile . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("View file not found: " . $viewPath);
        }
    }
} 