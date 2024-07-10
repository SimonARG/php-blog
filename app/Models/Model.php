<?php

namespace App\Models;

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = $GLOBALS['db'];
    }
}