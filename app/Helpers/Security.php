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

    public function isElevatedUser() : int
    {
        if(!(($_SESSION['role'] == 'admin') || ($_SESSION['role'] == 'mod'))) {
            return 0;
        }

        return 1;
    }

    public function canPost() : int
    {
        if(($_SESSION['role'] == 'admin') || ($_SESSION['role'] == 'mod') || ($_SESSION['role'] == 'poster')) {
            return 1;
        }

        return 0;
    }

    public function canComment() : int
    {
        if(($_SESSION['role'] == 'restricted') || ($_SESSION['role'] == 'banned')) {
            return 0;
        }

        return 1;
    }

    public function generateCsrf() : void
    {
        $_SESSION['csrf'] = md5(uniqid(mt_rand(), true));
    }

    public function verifyCsrf($csrf) : int
    {
        if (!$csrf || $csrf !== $_SESSION['csrf']) {
            return 0;
        }

        return 1;
    }
}