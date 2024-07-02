<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

class ConsequencesSeeder
{
    protected $db;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
    }

    public function run()
    {
        $consequences = [
            'none',
            'warning',
            'deleted resource',
            'restricted user',
            'banned user'
        ];

        foreach ($consequences as $key => $consequence) {
            $sql = "INSERT INTO consequences (consequence) VALUE (:consequence);";
            $this->db->query($sql, [':consequence' => $consequence]);
        }
    }
}