<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

class Seeder
{
    protected $db;

    public function __construct()
    {
        $this->db = $GLOBALS['db'];
    }
}
