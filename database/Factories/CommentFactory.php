<?php

namespace Database\Factories;

use Database\Factories\Factory;

class CommentFactory extends Factory
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        return [
            'body' => $this->faker->paragraphs(1, true),
            'user_id' => rand(1, 12),
            'post_id' => rand(1, 60)
        ];
    }
}
