<?php

namespace Database\Seeders;

use Database\Seeders\Seeder;
use Database\Factories\PostFactory;

class PostSeeder extends Seeder
{
    protected $postFactory;

    public function __construct()
    {
        parent::__construct();

        $this->postFactory = new PostFactory();
    }

    public function run()
    {
        for ($i = 0; $i < 60; $i++) {
            $post = $this->postFactory->create();
            $sql = "INSERT INTO posts (title, subtitle, thumb, body, user_id) VALUES (:title, :subtitle, :thumb, :body, :user_id)";
            $this->db->query($sql, $post);
        }
    }
}
