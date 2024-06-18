<?php

namespace App\Factories;

use Faker\Factory;

class PostFactory
{
    public static function create()
    {
        $faker = Factory::create();

        return [
            'title' => $faker->sentence,
            'subtitle' => $faker->sentence,
            'thumb' => rand(1, 7) . '.webp',
            'body' => $faker->paragraphs(7, true),
            'user_id' => rand(1, 12)
        ];
    }
}
