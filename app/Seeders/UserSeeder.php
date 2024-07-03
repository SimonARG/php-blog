<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

use App\Factories\UserFactory;
use App\Models\User;

class UserSeeder
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
        $this->userModel = new User();
    }

    public function run()
    {
        for ($i = 0; $i < 14; $i++) {
            $user = UserFactory::create();
            $sql = "INSERT INTO users (name, email, password, avatar) VALUES (:name, :email, :password, :avatar)";
            $this->db->query($sql, $user);
        }

        $sql = "INSERT INTO users (name, email, password, avatar) VALUES (:name, :email, :password, :avatar)";

        $password = password_hash('admin', PASSWORD_DEFAULT);

        $this->db->query($sql, [
            ':name' => 'admingod',
            ':email' => 'admingod@gmail.com',
            ':password' => $password,
            ':avatar' => '1.webp'
        ]);

        $this->userModel->setRole(15, 'admin');
    }
}