<?php

namespace App\Jobs;

use App\Models\User;

$userModel = new User();
$baseUrl = $GLOBALS['config']['base_url'];

$result = $this->userModel->hardDelete();
$deletionList = $result[0];
$avatarList = $result[1];

foreach ($avatarList as $key => $avatar) {
    $fileName = $avatar['avatar'];
    $filePath = __DIR__ . '/../../public/imgs/avatars/' . $fileName;

    $realPath = realpath($filePath);
    if ($realPath && is_writable($realPath)) {
        unlink($realPath);
    }
}

$logEntries = [];
if($deletionList) {
    foreach ($deletionList as $key => $userId) {
        $logEntries[$key] = 'User ' . $userId['id'] . ' hard deleted at ' . date('Y-m-d H:i:s');
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