<?php

namespace App\Seeders;

use App\Seeders\Seeder;
use App\Factories\CommentFactory;

class CommentSeeder extends Seeder
{
    public function __construct()
    {
        parent::__construct();
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