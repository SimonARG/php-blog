<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Seeders\UserSeeder;
use App\Seeders\PostSeeder;
use App\Seeders\RoleSeeder;
use App\Seeders\CommentSeeder;
use App\Seeders\RoleUserSeeder;

echo "Which seeder do you want to run? (1: All, 2: Users, 3: Posts, 4: Roles, 5: Comments): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

switch ($choice) {
    case '1':
        $userSeeder = new UserSeeder();
        $userSeeder->run();
        echo "Users seeded successfully.\n";

        $postSeeder = new PostSeeder();
        $postSeeder->run();
        echo "Posts seeded successfully.\n";

        $roleSeeder = new RoleSeeder();
        $roleSeeder->run();
        echo "Roles seeded successfully.\n";

        $roleUserSeeder = new RoleUserSeeder();
        $roleUserSeeder->run();
        echo "Role_User seeded successfully.\n";

        $commentSeeder = new CommentSeeder();
        $commentSeeder->run();
        echo "Comments seeded successfully.\n";
        break;
    case '2':
        $userSeeder = new UserSeeder();
        $userSeeder->run();
        echo "Users seeded successfully.\n";
        break;
    case '3':
        $postSeeder = new PostSeeder();
        $postSeeder->run();
        echo "Posts seeded successfully.\n";
        break;
    case '4':
        $roleSeeder = new RoleSeeder();
        $roleSeeder->run();
        echo "Role seeded successfully.\n";
        break;
    case '5':
        $commentSeeder = new CommentSeeder();
        $commentSeeder->run();
        echo "Comments seeded successfully.\n";
        break;
    case '6':
        $roleUserSeeder = new RoleUserSeeder();
        $roleUserSeeder->run();
        echo "Role_User seeded successfully.\n";
        break;
    default:
        echo "Invalid choice.\n";
        break;
}