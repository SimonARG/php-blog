<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

class RoleSeeder
{
    protected $db;
    protected $roles = ['admin', 'mod', 'poster', 'user', 'restricted', 'banned'];

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
    }

    public function run()
    {
        foreach ($this->roles as $key => $role) {
            $sql = "INSERT INTO roles (role) VALUES (:role)";
            $this->db->query($sql, [':role' => $role]);
        }
    }
}
