<?php

namespace Database\Seeders;

use Database\Seeders\Seeder;

class RoleSeeder extends Seeder
{
    protected $roles;

    public function __construct()
    {
        parent::__construct();

        $this->roles = ['admin', 'mod', 'poster', 'user', 'restricted', 'banned'];
    }

    public function run()
    {
        foreach ($this->roles as $key => $role) {
            $sql = "INSERT INTO roles (role) VALUES (:role)";
            $this->db->query($sql, [':role' => $role]);
        }
    }
}
