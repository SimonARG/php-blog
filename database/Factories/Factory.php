<?php

namespace Database\Factories;

use Faker\Factory as Generator;

class Factory
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Generator::create();
    }
}
