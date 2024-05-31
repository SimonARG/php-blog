<?php

namespace App\Seeders;

require_once __DIR__ . '/../bootstrap.php';

use App\Factories\PostFactory;

class PostSeeder
{
    protected $db;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];
    }

    public function run()
    {
        for ($i = 0; $i < 50; $i++) {
            $post = PostFactory::create();
            $sql = "INSERT INTO posts (title, subtitle, thumb, body, user_id) VALUES (:title, :subtitle, :thumb, :body, :user_id)";
            $this->db->query($sql, $post);
        }
    }
}
