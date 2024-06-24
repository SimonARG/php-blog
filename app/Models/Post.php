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
                users.name AS username,
                COUNT(comments.id) AS comments
            FROM posts
            INNER JOIN users ON posts.user_id = users.id
            LEFT JOIN comments ON posts.id = comments.post_id AND comments.deleted_at IS NULL
            WHERE posts.deleted_at IS NULL
            GROUP BY posts.id, users.name
            ORDER BY created_at DESC
            LIMIT :offset, :limit;";
        
        // Bind parameters with explicit data types
        return $this->db->fetchAll($sql, [
            ':limit' => $this->postsPerPage,
            ':offset' => $offset
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);
    }

    public function getPostCount()
    {
        $sql = "SELECT COUNT(*) FROM posts WHERE deleted_at IS NULL";
        return $this->db->fetch($sql)['COUNT(*)'];
    }

    public function getPostById($id)
    {
        $sql = "SELECT posts.*,
            users.name AS username,
            COUNT(comments.id) AS comments
        FROM posts 
        INNER JOIN users ON posts.user_id = users.id
        LEFT JOIN comments ON posts.id = comments.post_id AND comments.deleted_at IS NULL
        WHERE posts.id = :id";
        
        // Bind parameters with explicit data types
        return $this->db->fetch($sql, [
            ':id' => $id
        ], [
            ':id' => \PDO::PARAM_INT
        ]);
    }

    public function getPostByTitle($title)
    {
        $sql = "SELECT posts.*, users.name AS username 
        FROM posts 
        INNER JOIN users ON posts.user_id = users.id
        WHERE title = :title";
        
        $result = $this->db->fetch($sql, [
            ':title' => $title
        ]);

        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function create($data)
    {
        $sql = "INSERT INTO posts (title, subtitle, thumb, body, user_id) VALUES (:title, :subtitle, :thumb, :body, :user_id)";
        return $this->db->query($sql, [
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':thumb' => $data['thumb'],
            ':body' => $data['body'],
            ':user_id' => $data['user_id']
        ]);
    }

    public function update($data, $id)
    {
        $sql = "UPDATE posts SET title = :title, subtitle = :subtitle, thumb = :thumb, body = :body WHERE id = :id";
        return $this->db->query($sql, [
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':thumb' => $data['thumb'],
            ':body' => $data['body'],
            ':id' => $id,
        ]);
    }

    public function softDelete($id)
    {
        $currentTime = date('Y-m-d H:i:s');

        $sql = "UPDATE posts SET deleted_at = :deleted_at WHERE id = :id";
        return $this->db->query($sql, [
            ':deleted_at' => $currentTime,
            ':id' => $id
        ]);
    }

    public function search($query, $currentPage = 1)
    {
        $offset = ($currentPage - 1) * $this->postsPerPage;

        $sql = "SELECT posts.*,
                users.name AS username,
                COUNT(comments.id) AS comments
            FROM posts
            INNER JOIN users ON posts.user_id = users.id
            LEFT JOIN comments ON posts.id = comments.post_id AND comments.deleted_at IS NULL
            WHERE posts.deleted_at IS NULL
                AND (posts.title LIKE :search
                OR posts.subtitle LIKE :search
                OR posts.body LIKE :search
                OR users.name LIKE :search)
            GROUP BY posts.id, users.name
            ORDER BY created_at DESC
            LIMIT :offset, :limit;";

        // Bind parameters with explicit data types
        $result = $this->db->fetchAll($sql, [
            ':limit' => $this->postsPerPage,
            ':offset' => $offset,
            'search' => '%' . $query . '%'
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);

        $sql = "SELECT COUNT(*)
                FROM posts
                INNER JOIN users ON posts.user_id = users.id
                WHERE posts.deleted_at IS NULL
                    AND (posts.title LIKE :search
                    OR posts.subtitle LIKE :search
                    OR posts.body LIKE :search
                    OR users.name LIKE :search)";

        $count = $this->db->fetch($sql, [
            'search' => '%' . $query . '%'
        ])['COUNT(*)'];

        if($result) {
            return [
                'count' => $count,
                'posts' => $result
            ];
        } else {
            return 0;
        }
    }
}
