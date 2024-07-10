<?php

namespace App\Helpers;

class Security
{
    public function verifyIdentity(int $resourceOwnerId) : int
    {
        if(!($resourceOwnerId == $_SESSION['user_id']) && !($_SESSION['role'] == 'admin') && !($_SESSION['role'] == 'mod')) {
            return 0;
        }

        return 1;
    }

    public function isElevatedUser(int $userId) : int
    {
        if(!(isset($_SESSION))) {
            return 0;
        }

        if(!(($_SESSION['user_role'] == 'admin') || ($_SESSION['user_role'] == 'mod'))) {
            return 0;
        }

        return 1;
    }

    public function generateCsrf() : void
    {
        $_SESSION['csrf'] = md5(uniqid(mt_rand(), true));
    }

    public function verifyCsrf($csrf) : void
    {
        if (!$csrf || $csrf !== $_SESSION['csrf']) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
            exit;
        }
    }
}