<?php

function verifyIdentity($resourceOwnerId)
{
    if(!($resourceOwnerId == $_SESSION['user_id']) && !($_SESSION['role'] == 'admin') && !($_SESSION['role'] == 'mod')) {
        return 0;
    }

    return 1;
}