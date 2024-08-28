<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Database\Seeders\PostSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\ReportsSeeder;
use Database\Seeders\RoleUserSeeder;
use Database\Seeders\ConsequencesSeeder;
use Database\Seeders\ReportedResourcesSeeder;
use Database\Seeders\ConfigSeeder;
use Database\Seeders\InitialSeeder;

echo "Which seeder do you want to run? (1: All, 2: Initial, 3: Roles, 4: Users, 5: Posts, 6: Role-User, 7: Comments, 8: Consequences, 9: Reported Resources, 10: Reports, 11: Config): ";
$handle = fopen("php://stdin", "r");
$choice = trim(fgets($handle));

switch ($choice) {
    case '1':
        $roleSeeder = new RoleSeeder();
        $roleSeeder->run();
        echo "Roles seeded successfully.\n";

        $userSeeder = new UserSeeder();
        $userSeeder->run();
        echo "Users seeded successfully.\n";

        $postSeeder = new PostSeeder();
        $postSeeder->run();
        echo "Posts seeded successfully.\n";

        $roleUserSeeder = new RoleUserSeeder();
        $roleUserSeeder->run();
        echo "Role_User seeded successfully.\n";

        $commentSeeder = new CommentSeeder();
        $commentSeeder->run();
        echo "Comments seeded successfully.\n";

        $consequencesSeeder = new ConsequencesSeeder();
        $consequencesSeeder->run();
        echo "Consequences seeded successfully.\n";

        $reportedResourcesSeeder = new ReportedResourcesSeeder();
        $reportedResourcesSeeder->run();
        echo "Reported resources seeded successfully.\n";

        $reportsSeeder = new ReportsSeeder();
        $reportsSeeder->run();
        echo "Reports seeded successfully.\n";

        $configSeeder = new ConfigSeeder();
        $configSeeder->run();
        echo "Config seeded successfully.\n";
        break;
    case '2':
        $roleSeeder = new RoleSeeder();
        $roleSeeder->run();

        $initialSeeder = new InitialSeeder();
        $initialSeeder->run();

        $consequencesSeeder = new ConsequencesSeeder();
        $consequencesSeeder->run();

        $configSeeder = new ConfigSeeder();
        $configSeeder->run();
        echo "Initial config seeded successfully.\n";
        break;
    case '3':
        $roleSeeder = new RoleSeeder();
        $roleSeeder->run();
        echo "Roles seeded successfully.\n";
        break;
    case '4':
        $userSeeder = new UserSeeder();
        $userSeeder->run();
        echo "Users seeded successfully.\n";
        break;
    case '5':
        $postSeeder = new PostSeeder();
        $postSeeder->run();
        echo "Posts seeded successfully.\n";
        break;
    case '6':
        $roleUserSeeder = new RoleUserSeeder();
        $roleUserSeeder->run();
        echo "Role_User seeded successfully.\n";
        break;
    case '7':
        $commentSeeder = new CommentSeeder();
        $commentSeeder->run();
        echo "Comments seeded successfully.\n";
        break;
    case '8':
        $consequencesSeeder = new ConsequencesSeeder();
        $consequencesSeeder->run();
        echo "Consequences seeded successfully.\n";
        break;
    case '9':
        $reportedResourcesSeeder = new ReportedResourcesSeeder();
        $reportedResourcesSeeder->run();
        echo "Reported resources seeded successfully.\n";
        break;
    case '10':
        $reportsSeeder = new ReportsSeeder();
        $reportsSeeder->run();
        echo "Reports seeded successfully.\n";
    case '11':
        $configSeeder = new ConfigSeeder();
        $configSeeder->run();
        echo "Config seeded successfully.\n";
    default:
        echo "Invalid choice.\n";
        break;
}