<?php

namespace Database\Seeders;

use Database\Seeders\Seeder;

class RoleUserSeeder extends Seeder
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        for ($i = 1; $i < 13; $i++) {
            $sql = "INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id)";
            $this->db->query($sql, [
                ':user_id' => $i,
                ':role_id' => 3
            ]);
        }
    }
}
