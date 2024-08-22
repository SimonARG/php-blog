<?php

namespace Database\Seeders;

use Database\Seeders\Seeder;
use Database\Factories\CommentFactory;

class CommentSeeder extends Seeder
{
    protected $commentFactory;

    public function __construct()
    {
        parent::__construct();

        $this->commentFactory = new CommentFactory();
    }

    public function run()
    {
        for ($i = 0; $i < 70; $i++) {
            $comment = $this->commentFactory->create();
            $sql = "INSERT INTO comments (body, user_id, post_id) VALUES (:body, :user_id, :post_id)";
            $this->db->query($sql, $comment);
        }
    }
}
