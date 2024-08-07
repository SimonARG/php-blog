<?php

namespace App\Helpers;

use DateTime;
use App\Models\Blog;
use InvalidArgumentException;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class Helpers
{
    protected $blogModel;
    protected $blogConfig;

    public function __construct()
    {
        $this->blogModel = new Blog();
        $this->blogConfig = $this->blogModel->getBlogConfig();
    }

    public function view(string $viewName, array $data = []) : void
    {
        // Extract data to variables
        extract($data);

        // Add blogConfig to views
        $blogConfig = $this->blogConfig;

        $converter = new GithubFlavoredMarkdownConverter([]);

        $convertedContent = $converter->convert($blogConfig['info']);
        $blogConfig['info'] = $convertedContent->getContent();

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

    public function setPopup(string $content) : void
    {
        $_SESSION['popup_content'] = $content;
    }

    public function processImage(string $sourcePath, string $destinationPath) : bool
    {
        $quality = 88;
        $maxWidth = 1000;
        
        // Get image dimensions
        list($width, $height) = getimagesize($sourcePath);
        
        // Check if resizing is needed
        if ($width > $maxWidth) {
            // Calculate the new height to maintain aspect ratio
            $newHeight = intval(($maxWidth / $width) * $height);
            
            // Construct the FFmpeg command to resize and convert to WebP
            $command = "ffmpeg -i " . escapeshellarg($sourcePath) . " -vf scale={$maxWidth}:-1 -quality {$quality} " . escapeshellarg($destinationPath);
        } else {
            // Construct the FFmpeg command to convert to WebP without resizing
            $command = "ffmpeg -i " . escapeshellarg($sourcePath) . " -quality {$quality} " . escapeshellarg($destinationPath);
        }
    
        // Execute the command
        $output = shell_exec($command);
    
        // Check if the file exists at the destination path
        if (file_exists($destinationPath)) {
            // Delete the original image
            if (unlink($sourcePath)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function formatDates($resource): array
    {
        if (!is_array($resource)) {
            throw new InvalidArgumentException('Input must be an array');
        }

        $formatDate = function ($date) {
            return (new DateTime($date))->format('Y/m/d H:i');
        };

        if (isset($resource[0]) && is_array($resource[0])) {
            // Handle array of resources
            foreach ($resource as &$item) {
                $item['created_at'] = $formatDate($item['created_at']);
                if (isset($item['updated_at'])) {
                    $item['updated_at'] = $formatDate($item['updated_at']);
                }
            }
        } else {
            // Handle single resource
            $resource['created_at'] = $formatDate($resource['created_at']);
            if (isset($resource['updated_at'])) {
                $resource['updated_at'] = $formatDate($resource['updated_at']);
            }
        }

        return $resource;
    }
}