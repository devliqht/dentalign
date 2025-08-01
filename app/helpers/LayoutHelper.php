<?php

class LayoutHelper
{
    /**
     * Render a view with the global layout
     *
     * @param string $viewFile - Path to the view file (without .php extension)
     * @param array $data - Data to pass to the view
     * @param array $layoutConfig - Configuration for the layout
     *
     *
     */
    public static function render($viewFile, $data = [], $layoutConfig = [])
    {
        extract($data);

        /*
         *   To start using the layoutConfig, you pass an array that looks like this:
         *   $layoutConfig = [
         *       "title" => "Login",
         *       "hideHeader" => true,
         *       "hideFooter" => true,
         *       "bodyClass" => 'bg-[#fef5e1]',
         *       "bodyStyle" => 'background-image: url("/public/bg.svg"); background-size: cover;'
         *    ];
         *
         *   Where "config_name" => "value". Should you wish not to pass any, there are defaults set in place.
         */
        $pageTitle = $layoutConfig["title"] ?? null;
        $bodyClass = $layoutConfig["bodyClass"] ?? "";
        $bodyStyle = $layoutConfig["bodyStyle"] ?? null;
        $mainClass = $layoutConfig["mainClass"] ?? "min-h-screen";
        $hideHeader = $layoutConfig["hideHeader"] ?? false;
        $hideGuestHeader = $layoutConfig["hideGuestHeader"] ?? false;
        $hideFooter = $layoutConfig["hideFooter"] ?? false;
        $hideSidebar = $layoutConfig["hideSidebar"] ?? false;
        $navigation = $layoutConfig["navigation"] ?? null;
        $additionalHead = $layoutConfig["additionalHead"] ?? null;
        $additionalScripts = $layoutConfig["additionalScripts"] ?? null;

        ob_start();

        $viewPath = __DIR__ . "/../views/" . $viewFile . ".php";
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("View file not found: " . $viewPath);
        }

        $content = ob_get_clean();

        include __DIR__ . "/../views/Layout.php";
    }

    /**
     * Render a view without layout (for AJAX requests, etc.)
     */
    public static function renderPartial($viewFile, $data = [])
    {
        extract($data);

        $viewPath = __DIR__ . "/../views/" . $viewFile . ".php";
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("View file not found: " . $viewPath);
        }
    }
}
