<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

class ReportedResourcesSeeder
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
            $field = random_int(1, 3);

            if ($field == 1) { $field = 'post_id'; }
            if ($field == 2) { $field = 'user_id'; }
            if ($field == 3) { $field = 'comment_id'; }

            $sql = "INSERT INTO reported_resources ($field) VALUES (:id)";
            $this->db->query($sql, [
                ':id' => $resource
            ]);
        }
    }
}
