<?php

namespace App\Seeders;

class Seeder
{
    protected $db;

    public function __construct()
    {
        $this->db = $GLOBALS['db'];
    }
}