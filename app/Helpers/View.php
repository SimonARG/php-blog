<?php

if (!function_exists('view')) {
    function view($viewName, $data = [])
    {
        // Extract data to variables
        extract($data);

        // Add base URL to the extracted data
        $baseUrl = $GLOBALS['config']['base_url'];

        // Capture the view output
        ob_start();
        $viewPath = __DIR__ . '/../Templates/Views/' . str_replace('.', '/', $viewName) . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            die("View {$viewName} not found.");
        }
        $content = ob_get_clean();

        // Include the layout and pass the content
        $layoutPath = __DIR__ . '/../Templates/Layouts/website.php';
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            die("Layout not found.");
        }
    }
}