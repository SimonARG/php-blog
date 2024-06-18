<?php

namespace App\Factories;

use Faker\Factory;

class CommentFactory
{
    public static function create()
    {
        $faker = Factory::create();

        return [
            'body' => $faker->paragraphs(1, true),
            'user_id' => rand(1, 12),
            'post_id' => rand(1, 60)
        ];
    }
}
