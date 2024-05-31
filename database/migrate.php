<?php

require_once __DIR__ . '/../app/bootstrap.php';

echo "Do you want to migrate (1: up, 2: down): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

$migrations = [
    'CreateUsersTable' => __DIR__ . '/migrations/1_create_users_table.php',
    'CreatePostsTable' => __DIR__ . '/migrations/2_create_posts_table.php'
];

foreach ($migrations as $migrationClass => $filePath) {
    require_once $filePath;
    $migration = new $migrationClass();

    switch ($choice) {
        case '1':
            $migration->up();
            echo "$migrationClass migrated up successfully.\n";
            break;
        case '2':
            $migration->down();
            echo "$migrationClass migrated down successfully.\n";
            break;
        default:
            echo "Invalid choice.\n";
            break;
    }
}