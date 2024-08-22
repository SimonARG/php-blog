<?php

namespace Database\Seeders;

use Database\Seeders\Seeder;

class ConsequencesSeeder extends Seeder
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $consequences = [
            'none',
            'warning',
            'modified resource',
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
