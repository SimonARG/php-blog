<?php

namespace App\Factories;

use Faker\Generator;

class Factory
{
    protected $faker;

    public function __construct()
    {
        $this->faker = new Generator();
    }
}