<?php

namespace Database\Seeders;

require_once __DIR__ . '/../bootstrap.php';

abstract class Seeder
{
    protected $db;

    public function __construct()
    {
        $this->db = $GLOBALS['db'];
    }
}
