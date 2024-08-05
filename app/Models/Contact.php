<?php

namespace App\Models;

use App\Models\Model;

class Contact extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getContacts() : array|int
    {
        $sql = "SELECT * FROM contact;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }
}