<?php

namespace App\Jobs;

use App\Models\Post;

$postModel = new Post();
$baseUrl = $GLOBALS['config']['base_url'];

$result = $this->postModel->hardDelete();
$deletionList = $result[0];
$thumbList = $result[1];

foreach ($thumbList as $key => $value) {
    $fileName = $value['thumb'];
    $filePath = __DIR__ . '/../../public/imgs/thumbs/' . $fileName;

    $realPath = realpath($filePath);
    if ($realPath && is_writable($realPath)) {
        unlink($realPath);
    }
}

$logEntries = [];
if($deletionList) {
    foreach ($deletionList as $key => $postId) {
        $logEntries[$key] = 'Post ' . $postId['id'] . ' hard deleted at ' . date('Y-m-d H:i:s');
    }
}

__DIR__ . '/../../logs/deletions.txt';
$logFile = __DIR__ . '/../../logs/deletions.txt';
$currentLog = file_exists($logFile) ? file_get_contents($logFile) : '';
$newLog = implode("\n", $logEntries) . ($currentLog ? "\n" . $currentLog : '');
file_put_contents($logFile, $newLog);

// HOW TO RUN ON WINDOWS
// Open Windows Task Scheduler 
// Create a new task
// Set the action to start a program
// Program/script: Full\path\to\php\folder\php.exe
// Add arguments: -f Full\path\to\the\script.php
// Set the trigger to run daily