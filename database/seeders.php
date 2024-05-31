<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Seeders\UserSeeder;
use App\Seeders\PostSeeder;

echo "Which seeder do you want to run? (1: Users, 2: Posts, 3: Both): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

switch ($choice) {
    case '1':
        $userSeeder = new UserSeeder();
        $userSeeder->run();
        echo "Users seeded successfully.\n";
        break;
    case '2':
        $postSeeder = new PostSeeder();
        $postSeeder->run();
        echo "Posts seeded successfully.\n";
        break;
    case '3':
        $userSeeder = new UserSeeder();
        $userSeeder->run();
        echo "Users seeded successfully.\n";

        $postSeeder = new PostSeeder();
        $postSeeder->run();
        echo "Posts seeded successfully.\n";
        break;
    default:
        echo "Invalid choice.\n";
        break;
}