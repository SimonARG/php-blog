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

    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        return $this->db->fetchAll($sql);
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users
        WHERE users.email = :email";

        $result = $this->db->fetch($sql, [
            ':email' => $email
        ]);

        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function getUserByEmailWithRole($email)
    {
        $sql = "SELECT users.*, roles.role AS role 
        FROM users
        INNER JOIN role_user ON users.id = role_user.user_id
        INNER JOIN roles ON role_user.role_id = roles.id
        WHERE users.email = :email";

        $result = $this->db->fetch($sql, [
            ':email' => $email
        ]);

        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function getUserById($id)
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

        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }

    public function create($data)
    {
        $sql = "INSERT INTO users (name, email, password, avatar) VALUES (:name, :email, :password, 'avatar.jpg')";

        return $this->db->query($sql, [
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
        ]);
    }

    public function getUserCount()
    {
        $sql = "SELECT COUNT(*) as count FROM users;";

        $result = $this->db->fetch($sql);

        return $result ? $result : 0;
    }

    public function setRole($user_id, $roleName)
    {
        $sql = "SELECT id FROM roles
        WHERE roles.role = :role";

        $role = $this->db->fetch($sql, [
            ':role' => $roleName
        ]);
        
        $sql = "INSERT INTO role_user (user_id, role_id) VALUES (:user_id, :role_id)";
        return $this->db->query($sql, [
            ':user_id' => $user_id,
            ':role_id' => $role['id']
        ]);
    }

    public function getUserPosts($id, $currentPage = 1)
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

        if($result) {
            return [
                'count' => $count,
                'posts' => $result
            ];
        } else {
            return 0;
        }
    }

    public function getLatestUserPostId($id)
    {
        $sql = "SELECT id FROM posts
                WHERE user_id = :id
                AND deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT 1";
        
        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : 0;
    }

    public function getUserComments($id)
    {
        $sql = "SELECT * FROM comments
                WHERE user_id = :id
                AND deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT 1";
        
        $result = $this->db->fetchAll($sql, [':id' => $id]);

        return $result ? $result : 0;
    }

    public function getLatestUserCommentId($id)
    {
        $sql = "SELECT id FROM comments
                WHERE user_id = :id
                AND deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT 1";
        
        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : ['id' => 0];
    }

    public function getSavedPostsCount($id)
    {
        $sql = "SELECT COUNT(saved_posts.user_id) as posts 
                FROM saved_posts 
                JOIN posts ON saved_posts.post_id = posts.id
                WHERE saved_posts.user_id = :id AND posts.deleted_at IS NULL";
        
        $result = $this->db->fetch($sql, [':id' => $id]);

        return $result ? $result : ['posts' => 0];
    }

    public function getSavedPostsIds($id)
    {
        $sql = "SELECT saved_posts.post_id as post
                FROM saved_posts
                JOIN posts ON saved_posts.post_id = posts.id
                WHERE saved_posts.user_id = :id AND posts.deleted_at IS NULL";
        
        $result = $this->db->fetchAll($sql, [':id' => $id]);

        return $result ? $result : 0;
    }

    public function getSavedPosts($id, $currentPage = 1)
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

        if($result) {
            return [
                'count' => $count,
                'posts' => $result
            ];
        } else {
            return 0;
        }
    }

    public function update($data, $id)
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

        return $this->db->query($sql, $params, [
            ':id' => \PDO::PARAM_INT
        ]);
    }

    public function changeRole($userId, $roleId)
    {
        $sql = "UPDATE role_user SET role_id = :role_id WHERE user_id = :user_id;";

        $result = $this->db->query($sql, [
            ':role_id' => $roleId,
            ':user_id' => $userId
        ]);

        return $result ? 1 : 0;
    }
}