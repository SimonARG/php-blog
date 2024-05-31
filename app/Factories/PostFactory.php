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
            'thumb' => 'thumb.avif',
            'body' => $faker->paragraphs(3, true),
            'user_id' => rand(1, 10)
        ];
    }
}
