<?php

namespace App\Models;

use App\Models\Model;

class User extends Model
{
    protected $postsPerPage;

    public function __construct()
    {
        parent::__construct();
        $this->postsPerPage = $GLOBALS['config']['posts_per_page'];
    }

    public function getUserByEmail(string $email) : array|bool
    {
        $sql = "SELECT users.* FROM users
        WHERE users.email = :email";

        $result = $this->db->fetch($sql, [
            ':email' => $email
        ]);

        return $result ? $result : false;
    }

    public function getUserByEmailWithRole(string $email) : array|bool
    {
        $sql = "SELECT users.*, roles.role AS role 
        FROM users
        INNER JOIN role_user ON users.id = role_user.user_id
        INNER JOIN roles ON role_user.role_id = roles.id
        WHERE users.email = :email";

        $result = $this->db->fetch($sql, [
            ':email' => $email
        ]);

        return $result ? $result : false;
    }

    public function getUserById(int $id) : array|bool
    {
        $sql = "SELECT 
                users.*, 
                roles.role AS role, 
                (SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id AND posts.deleted_at IS NULL) AS posts, 
                (SELECT COUNT(*) FROM comments WHERE comments.user_id = users.id AND comments.deleted_at IS NULL) AS comments
            FROM users
            LEFT JOIN role_user ON users.id = role_user.user_id
            LEFT JOIN roles ON role_user.role_id = roles.id
            WHERE users.id = :id
            AND users.deleted_at IS NULL";

        $result = $this->db->fetch($sql, [
            ':id' => $id
        ]);

        return $result ? $result : false;
    }

    public function create(array $data) : object|bool
    {
        $sql = "INSERT INTO users (name, email, password, avatar) VALUES (:name, :email, :password, 'avatar.jpg')";

        $result = $this->db->query($sql, [
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
        ]);

        return $result ? $result : false;
    }

    public function getUserCount() : array|bool
    {
        $sql = "SELECT COUNT(*) as count FROM users;";

        $result = $this->db->fetch($sql);

        return $result ? $result : 0;
    }

    public function setRole(int $userId, string $roleName) : object|bool
    {
        $sql = "SELECT id FROM roles
        WHERE roles.role = :role";

        $role = $this->db->fetch($sql, [
            ':role' => $roleName
        ]);
        
        $sql = "INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id)";

        $result = $this->db->query($sql, [
            ':user_id' => $userId,
            ':role_id' => $role['id']
        ]);

        return $result ? $result : false;
    }

    public function getUserPosts(int $id, int $currentPage = 1) : array|bool
    {
        $offset = ($currentPage - 1) * $this->postsPerPage;

        $sql = "SELECT posts.*,
                users.name AS username,
                COUNT(comments.id) AS comments
            FROM posts
            INNER JOIN users ON posts.user_id = users.id
            LEFT JOIN comments ON posts.id = comments.post_id AND comments.deleted_at IS NULL
            WHERE posts.deleted_at IS NULL
                AND posts.user_id = :id
            GROUP BY posts.id
            ORDER BY created_at DESC
            LIMIT :offset, :limit;";

        // Bind parameters with explicit data types
        $result = $this->db->fetchAll($sql, [
            ':limit' => $this->postsPerPage,
            ':offset' => $offset,
            ':id' => $id
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);

        $sql = "SELECT COUNT(*)
                FROM posts
                WHERE posts.deleted_at IS NULL
                AND posts.user_id = :id";

        $count = $this->db->fetch($sql, [
            ':id' => $id
        ])['COUNT(*)'];

        return $result ? ['count' => $count, 'posts' => $result] : false;
    }

    public function getLatestUserPostId(int $id) : array|bool
    {
        $sql = "SELECT id FROM posts
                WHERE user_id = :id
                AND deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT 1";
        
        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : 0;
    }

    public function getUserComments(int $id) : array|bool
    {
        $sql = "SELECT * FROM comments
                WHERE user_id = :id
                AND deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT 1";
        
        $result = $this->db->fetchAll($sql, [':id' => $id]);

        return $result ? $result : 0;
    }

    public function getLatestUserCommentId(int $id) : array
    {
        $sql = "SELECT id FROM comments
                WHERE user_id = :id
                AND deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT 1";
        
        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : ['id' => 0];
    }

    public function getSavedPostsCount(int $id) : array
    {
        $sql = "SELECT COUNT(saved_posts.user_id) as posts 
                FROM saved_posts 
                JOIN posts ON saved_posts.post_id = posts.id
                WHERE saved_posts.user_id = :id AND posts.deleted_at IS NULL";
        
        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : ['posts' => 0];
    }

    public function getSavedPostsIds(int $id) : array|bool
    {
        $sql = "SELECT saved_posts.post_id as post
                FROM saved_posts
                JOIN posts ON saved_posts.post_id = posts.id
                WHERE saved_posts.user_id = :id AND posts.deleted_at IS NULL";
        
        $result = $this->db->fetchAll($sql, [':id' => $id]);

        return $result ? $result : 0;
    }

    public function getSavedPosts(int $id, int $currentPage = 1) : array|bool
    {
        $offset = ($currentPage - 1) * $this->postsPerPage;

        $sql = "SELECT posts.*,
                users.name AS username,
                COUNT(comments.id) AS comments
            FROM posts
            JOIN saved_posts ON posts.id = saved_posts.post_id
            INNER JOIN users ON posts.user_id = users.id
            LEFT JOIN comments ON posts.id = comments.post_id AND comments.deleted_at IS NULL
            WHERE posts.deleted_at IS NULL
                AND saved_posts.user_id = :id
            GROUP BY posts.id
            ORDER BY created_at DESC
            LIMIT :offset, :limit";

        // Bind parameters with explicit data types
        $result = $this->db->fetchAll($sql, [
            ':limit' => $this->postsPerPage,
            ':offset' => $offset,
            ':id' => $id
        ], [
            ':limit' => \PDO::PARAM_INT,
            ':offset' => \PDO::PARAM_INT
        ]);

        $sql = "SELECT COUNT(user_id) as posts FROM saved_posts
                WHERE user_id = :id";
        
        $count = $this->db->fetch($sql, [
            ':id' => $id
        ])['posts'];

        return $result ? ['count' => $count, 'posts' => $result] : false;
    }

    public function update(array $data, string $id) : object|bool
    {
        $sql = "UPDATE users SET ";
        $params = NULL;

        if($data['name']) {
            $sql .= "name = :name";
            $params['name'] = $data['name'];
        }

        if($data['email']) {
            $sql .= " ,email = :email";
            $params['email'] = $data['email'];
        }

        if($data['avatar']) {
            $sql .= " ,avatar = :avatar";
            $params['avatar'] = $data['avatar'];
        }

        if(isset($data['password'])) {
            $sql .= " ,password = :password";
            $params['password'] = $data['password'];
        }

        $params['id'] = (int)$id;

        $sql .= " WHERE id = :id";

        $result = $this->db->query($sql, $params, [
            ':id' => \PDO::PARAM_INT
        ]);

        return $result ? $result : false;
    }

    public function changeRole(int $userId, int $roleId) : int
    {
        $sql = "UPDATE role_user SET role_id = :role_id WHERE user_id = :user_id;";

        $result = $this->db->query($sql, [
            ':role_id' => $roleId,
            ':user_id' => $userId
        ]);

        return $result ? 1 : 0;
    }
}