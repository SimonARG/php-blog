<?php

// This script is designed to be run from the command line
if (php_sapi_name() !== 'cli') {
    exit("This script can only be run from the command line.\n");
}

function runScript($scriptPath) {
    if (file_exists($scriptPath)) {
        echo "Running $scriptPath...\n";
        // Use popen and fpassthru for better handling of interactive scripts on Windows
        $handle = popen("php \"$scriptPath\"", 'r');
        while (!feof($handle)) {
            echo fread($handle, 4096);
        }
        pclose($handle);
        echo "\nFinished running $scriptPath.\n";
    } else {
        echo "Error: $scriptPath not found.\n";
    }
}

function promptUser() {
    echo "\nPlease choose an option:\n";
    echo "1. Run migrations\n";
    echo "2. Run seeders\n";
    echo "3. Exit\n";
    echo "Enter your choice (1-3): ";
    $handle = fopen("php://stdin", "r");
    $choice = trim(fgets($handle));
    fclose($handle);
    return $choice;
}

$rootDir = dirname(__FILE__);
$migrateScript = $rootDir . '\database\migrate.php';
$seederScript = $rootDir . '\database\seeders.php';

while (true) {
    $choice = promptUser();

    switch ($choice) {
        case '1':
            runScript($migrateScript);
            break;
        case '2':
            runScript($seederScript);
            break;
        case '3':
            echo "Exiting. Goodbye!\n";
            exit(0);
        default:
            echo "Invalid choice. Please try again.\n";
    }
}