<?php

namespace App\Seeders;

use App\Seeders\Seeder;

class ReportsSeeder extends Seeder
{
    protected $resources;

    public function __construct()
    {
        parent::__construct();
        $this->resources = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
    }

    public function run()
    {
        foreach ($this->resources as $key => $resource) {
            $field = random_int(1, 14);

            $sql = "INSERT INTO reports (resource_id, reported_by) VALUES (:id, :reporter)";

            $this->db->query($sql, [
                ':id' => $resource,
                ':reporter' => $field
            ]);
        }
    }
}
