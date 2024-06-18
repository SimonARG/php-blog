<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

use App\Factories\UserFactory;

class UserSeeder
{
    protected $db;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
    }

    public function run()
    {
        for ($i = 0; $i < 12; $i++) {
            $user = UserFactory::create();
            $sql = "INSERT INTO users (name, email, password, avatar) VALUES (:name, :email, :password, :avatar)";
            $this->db->query($sql, $user);
        }
    }
}