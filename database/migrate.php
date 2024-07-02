<?php

require_once __DIR__ . '/../app/bootstrap.php';

echo "Do you want to migrate (1: up, 2: down): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

$migrations = [
    'CreateUsersTable' => __DIR__ . '/migrations/1_create_users_table.php',
    'CreatePostsTable' => __DIR__ . '/migrations/2_create_posts_table.php',
    'CreateRolesTable' => __DIR__ . '/migrations/3_create_roles_table.php',
    'CreateRoleUserTable' => __DIR__ . '/migrations/4_create_role_user_table.php',
    'CreateCommentsTable' => __DIR__ . '/migrations/5_create_comments_table.php',
    'CreateSavedPostsTable' => __DIR__ . '/migrations/6_create_saved_posts_table.php',
    'CreateReportedResourcesTable' => __DIR__ . '/migrations/7_create_reported_resources_table.php',
    'CreateConsequencesTable' => __DIR__ . '/migrations/8_create_consequences_table.php',
    'CreateModActionsTable' => __DIR__ . '/migrations/9_create_mod_actions_table.php',
    'CreateReportsTable' => __DIR__ . '/migrations/10_create_reports_table.php'
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