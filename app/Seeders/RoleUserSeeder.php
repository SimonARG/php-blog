<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

use App\Factories\PostFactory;

class RoleUserSeeder
{
    protected $db;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
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
