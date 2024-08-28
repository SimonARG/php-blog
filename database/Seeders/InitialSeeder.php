<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Seeder;

class InitialSeeder extends Seeder
{
    protected $user;

    public function __construct()
    {
        parent::__construct();

        $this->user = new User();
    }

    public function run()
    {
        $sql = "INSERT INTO users (name, email, password, avatar) VALUES (:name, :email, :password, :avatar)";

        $password = password_hash('admin', PASSWORD_DEFAULT);

        $this->db->query($sql, [
            ':name' => 'admin',
            ':email' => 'admin@gmail.com',
            ':password' => $password,
            ':avatar' => 'avatar.jpg'
        ]);

        $this->user->setRole(15, 'admin');
    }
}
