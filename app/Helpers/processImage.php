<?php

function processImage($sourcePath, $destinationPath) {
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
            return "Image resized, converted to AVIF, and original file deleted successfully.";
        } else {
            return "Image resized and converted to AVIF, but failed to delete the original file.";
        }
    } else {
        return "Error: " . $output;
    }
}