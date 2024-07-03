<?php

namespace App\Factories;

use Faker\Factory;

class UserFactory
{
    public static function create()
    {
        $faker = Factory::create();

        return [
            'name' => $faker->unique()->userName(),
            'email' => $faker->unique()->safeEmail(),
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'avatar' => rand(1, 7) . '.webp',
        ];
    }
}