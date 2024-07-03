<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

class ReportsSeeder
{
    protected $db;
    protected $resources;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
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
