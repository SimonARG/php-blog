<?php

namespace App\Helpers;

class Security
{
    public function verifyIdentity($resourceOwnerId)
    {
        if(!($resourceOwnerId == $_SESSION['user_id']) && !($_SESSION['role'] == 'admin') && !($_SESSION['role'] == 'mod')) {
            return 0;
        }

        return 1;
    }

    public function isElevatedUser($userId)
    {
        if(!(isset($_SESSION))) {
            return 0;
        }

        if(!(($_SESSION['user_role'] == 'admin') || ($_SESSION['user_role'] == 'mod'))) {
            return 0;
        }

        return 1;
    }
}