<?php

namespace App\Models;

use App\Models\Model;

class Comment extends Model
{
    protected $commentsPerPage;

    public function __construct()
    {
        parent::__construct();
        $this->commentsPerPage = $GLOBALS['config']['comments_per_page'];
    }

    public function getCommentsForPost(int $id, int $currentPage = 1): array|bool
    {
        $offset = ($currentPage - 1) * $this->commentsPerPage;

        $sql  = "SELECT comments.*,
                        users.name AS username,
                        users.id AS user_id,
                        users.avatar AS avatar
                FROM comments
                INNER JOIN users ON comments.user_id = users.id
                WHERE post_id = :id
                AND comments.deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT :offset,
                    :limit";

        // Bind parameters with explicit data types
        $result = $this->db->fetchAll($sql, [
            ':id' => $id,
            ':limit' => $this->commentsPerPage,
            ':offset' => $offset
        ], [
            ':id' => \PDO::PARAM_INT,
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);

        return $result ? $result : false;
    }

    public function getCommentById(int $id): array|bool
    {
        $sql = "SELECT * FROM comments WHERE id = :id";

        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : false;
    }

    public function getPostIdForComment(int $id): array|bool
    {
        $sql = "SELECT post_id
                FROM comments
                WHERE id = :id";

        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : false;
    }

    public function storeComment(array $comment): object|bool
    {
        $sql  = "INSERT INTO comments (body, user_id, post_id) VALUES (:body, :user_id, :post_id)";

        // Bind parameters with explicit data types
        $result = $this->db->query($sql, [
        ':body' => $comment['body'],
        ':user_id' => $comment['user_id'],
        ':post_id' => $comment['post_id']
        ], [
        ':body' => \PDO::PARAM_STR,
        ':user_id' => \PDO::PARAM_INT,
        ':post_id' => \PDO::PARAM_INT
        ]);

        return $result ? $result : false;
    }

    public function update(string $comment, int $id): object|bool
    {
        $sql = "UPDATE comments SET body = :body WHERE id = :id";

        $result = $this->db->query($sql, [
            ':body' => $comment,
            ':id' => $id,
        ]);

        return $result ? $result : false;
    }

    public function softDelete(int $id): object|bool
    {
        $currentTime = date('Y-m-d H:i:s');

        $sql = "UPDATE comments SET deleted_at = :deleted_at WHERE id = :id";

        $result =  $this->db->query($sql, [
            ':deleted_at' => $currentTime,
            ':id' => $id
        ]);

        return $result ? $result : false;
    }
}
