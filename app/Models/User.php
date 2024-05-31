<?php

namespace App\Models;

class User
{
    protected $db;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        return $this->db->fetchAll($sql);
    }
}