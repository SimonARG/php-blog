<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Seeder;
use Database\Factories\UserFactory;

class UserSeeder extends Seeder
{
    protected $userModel;
    protected $userFactory;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->userFactory = new UserFactory();
    }

    public function run()
    {
        for ($i = 0; $i < 14; $i++) {
            $user = $this->userFactory->create();
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
