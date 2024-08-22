<?php

namespace Database;

require __DIR__ . '/../vendor/autoload.php';

use Database\Migrations\CreateUsersTable;
use Database\Migrations\CreatePostsTable;
use Database\Migrations\CreateRolesTable;
use Database\Migrations\CreateRoleUserTable;
use Database\Migrations\CreateCommentsTable;
use Database\Migrations\CreateSavedPostsTable;
use Database\Migrations\CreateReportedResourcesTable;
use Database\Migrations\CreateConsequencesTable;
use Database\Migrations\CreateReportsTable;
use Database\Migrations\CreateModActionsTable;
use Database\Migrations\CreateConfigTable;
use Database\Migrations\CreateContactTable;
use Database\Migrations\CreateFriendsTable;
use Database\Migrations\CreateLinksTable;

echo "Do you want to migrate (1: up, 2: down): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

$migrations = [
    new CreateUsersTable(),
    new CreatePostsTable(),
    new CreateRolesTable(),
    new CreateRoleUserTable(),
    new CreateCommentsTable(),
    new CreateSavedPostsTable(),
    new CreateReportedResourcesTable(),
    new CreateConsequencesTable(),
    new CreateReportsTable(),
    new CreateModActionsTable(),
    new CreateConfigTable(),
    new CreateContactTable(),
    new CreateFriendsTable(),
    new CreateLinksTable(),
];

foreach ($migrations as $migration) {
    $migrationClassName = get_class($migration);

    switch ($choice) {
        case '1':
            $migration->up();
            echo "$migrationClassName migrated up successfully.\n";
            break;
        case '2':
            $migration->down();
            echo "$migrationClassName migrated down successfully.\n";
            break;
        default:
            echo "Invalid choice.\n";
            break;
    }
}