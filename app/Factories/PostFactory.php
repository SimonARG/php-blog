<?php

namespace App\Factories;

use App\Factories\Factory;

class PostFactory extends Factory
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        return [
            'title' => $this->faker->sentence,
            'subtitle' => $this->faker->sentence,
            'thumb' => rand(1, 7) . '.webp',
            'body' => $this->faker->paragraphs(7, true),
            'user_id' => rand(1, 12)
        ];
    }
}
