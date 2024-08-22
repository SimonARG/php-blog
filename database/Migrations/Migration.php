<?php

namespace Database\Migrations;

require_once __DIR__ . '/../bootstrap.php';

abstract class Migration
{
    protected $db;

    public function __construct()
    {
        $this->db = $GLOBALS['db'];
    }
}
