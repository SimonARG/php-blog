<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

use App\Factories\CommentFactory;

class CommentSeeder
{
    protected $db;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
    }

    public function run()
    {
        for ($i = 0; $i < 70; $i++) {
            $comment = CommentFactory::create();
            $sql = "INSERT INTO comments (body, user_id, post_id) VALUES (:body, :user_id, :post_id)";
            $this->db->query($sql, $comment);
        }
    }
}