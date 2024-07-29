<?php

namespace App\Models;

use App\Models\Model;

class Post extends Model
{
    protected $postsPerPage;

    public function __construct()
    {
        parent::__construct();
        $this->postsPerPage = $GLOBALS['config']['posts_per_page'];
    }

    public function getPosts(int $currentPage = 1) : array|bool
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
        $result = $this->db->fetchAll($sql, [
            ':limit' => $this->postsPerPage,
            ':offset' => $offset
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);

        return $result ? $result : false;
    }

    public function getPostCount() : int|bool
    {
        $sql = "SELECT COUNT(*) FROM posts WHERE deleted_at IS NULL";

        $result =  $this->db->fetch($sql)['COUNT(*)'];

        return $result ? $result : false;
    }

    public function getPostById(int $id) : array|bool
    {
        $sql = "SELECT posts.*,
            users.name AS username,
            COUNT(comments.id) AS comments
        FROM posts 
        INNER JOIN users ON posts.user_id = users.id
        LEFT JOIN comments ON posts.id = comments.post_id AND comments.deleted_at IS NULL
        WHERE posts.id = :id";
        
        // Bind parameters with explicit data types
        $result = $this->db->fetch($sql, [
            ':id' => $id
        ], [
            ':id' => \PDO::PARAM_INT
        ]);

        return $result ? $result : false;
    }

    public function getPostByTitle(string $title) : array|bool
    {
        $sql = "SELECT posts.*, users.name AS username 
        FROM posts
        INNER JOIN users ON posts.user_id = users.id
        WHERE title = :title";
        
        $result = $this->db->fetch($sql, [
            ':title' => $title
        ]);

        return $result ? $result : false;
    }

    public function create(array $data) : object|bool
    {
        $sql = "INSERT INTO posts (title, subtitle, thumb, body, user_id) VALUES (:title, :subtitle, :thumb, :body, :user_id)";

        $result = $this->db->query($sql, [
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':thumb' => $data['thumb'],
            ':body' => $data['body'],
            ':user_id' => $data['user_id']
        ]);

        return $result ? $result : false;
    }

    public function update(array $data, int $id) : object|bool
    {
        $sql = "UPDATE posts SET title = :title, subtitle = :subtitle, thumb = :thumb, body = :body WHERE id = :id";

        $result = $this->db->query($sql, [
            ':title' => $data['title'],
            ':subtitle' => $data['subtitle'],
            ':thumb' => $data['thumb'],
            ':body' => $data['body'],
            ':id' => $id,
        ]);

        return $result ? $result : false;
    }

    public function softDelete(int $id) : object|bool
    {
        $currentTime = date('Y-m-d H:i:s');

        $sql = "UPDATE posts SET deleted_at = :deleted_at WHERE id = :id";

        $result = $this->db->query($sql, [
            ':deleted_at' => $currentTime,
            ':id' => $id
        ]);

        return $result ? $result : false;
    }

    public function hardDelete() : array|bool
    {
        $sql = "SELECT id FROM posts WHERE deleted_at IS NOT NULL";

        $deletionList = $this->db->fetchAll($sql);

        $sql = "SELECT thumb FROM posts WHERE deleted_at IS NOT NULL";

        $thumbList = $this->db->fetchAll($sql);

        $sql = "DELETE FROM posts WHERE deleted_at IS NOT NULL";

        $result = $this->db->query($sql);

        return $result ? [$deletionList, $thumbList] : false;
    }

    public function search(string $query, int $currentPage = 1) : array|bool
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

        return $result ? ['count' => $count, 'posts' => $result] : false;
    }

    public function save(int $postId, int $userId) : bool
    {
        $sql = "INSERT INTO saved_posts (user_id, post_id)
        VALUES (:user_id, :post_id)";

        $result = $this->db->query($sql, [
            ':user_id' => $userId,
            ':post_id' => $postId
        ]);

        return $result ? true : false;
    }

    public function deleteSaved(int $postId, int $userId) : bool
    {
        $sql = "DELETE FROM saved_posts
                WHERE user_id = :user_id
                AND post_id = :post_id";

        $result = $this->db->query($sql, [
            ':user_id' => $userId,
            ':post_id' => $postId
        ]);

        return $result ? true : false;
    }
}
