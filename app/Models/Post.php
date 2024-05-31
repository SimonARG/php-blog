<?php

namespace App\Models;

class Post
{
    protected $db;
    protected $postsPerPage;

    public function __construct()
    {
        // Use the global database instance
        $this->db = $GLOBALS['db'];

        $this->postsPerPage = $GLOBALS['config']['posts_per_page'];
    }

    public function getPosts($currentPage = 1)
    {
        $offset = ($currentPage - 1) * $this->postsPerPage;
        $sql = "SELECT posts.*,
                    users.name AS username
                FROM posts
                INNER JOIN users ON posts.user_id = users.id
                ORDER BY created_at DESC
                LIMIT :offset,
                    :limit;";
        
        // Bind parameters with explicit data types
        return $this->db->fetchAll($sql, [
            ':limit' => $this->postsPerPage,
            ':offset' => $offset
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);
    }

    public function getAllPosts()
    {
        $sql = "SELECT COUNT(*) FROM posts";
        return $this->db->fetch($sql)['COUNT(*)'];
    }

    public function getPost($id)
    {
        $sql = "SELECT posts.*, users.name AS username 
        FROM posts 
        INNER JOIN users ON posts.user_id = users.id 
        WHERE posts.id = :id";
        
        // Bind parameters with explicit data types
        return $this->db->fetch($sql, [
            ':id' => $id
        ], [
            ':id' => \PDO::PARAM_INT
        ]);
    }
}
