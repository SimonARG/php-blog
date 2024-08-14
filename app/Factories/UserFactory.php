<?php

namespace App\Factories;

use App\Factories\Factory;

class UserFactory extends Factory
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        return [
            'name' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'avatar' => rand(1, 7) . '.webp',
        ];
    }
}
