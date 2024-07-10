<?php

namespace App\Factories;

use Faker\Generator;

class Factory
{
    public $faker;

    public function __construct()
    {
        $this->faker = new Generator();
    }
}