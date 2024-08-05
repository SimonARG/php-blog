<?php

namespace App\Models;

use App\Models\Model;

class Friend extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getFriends() : array|int
    {
        $sql = "SELECT * FROM friends;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }
}