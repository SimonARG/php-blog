<?php

namespace App\Models;

use App\Models\Model;

class Friend extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(): array|int
    {
        $sql = "SELECT * FROM friends;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }

    public function store(array $data): object|bool
    {
        $sql = "INSERT INTO friends (title, url, comment) VALUES (:title, :url, :comment);";
        $result = $this->db->query($sql, [
            ':title' => $data['title'],
            ':url' => $data['url'],
            ':comment' => $data['comment']
        ]);

        return $result ? $result : 0;
    }

    public function update(int $id, array $data): object|bool
    {
        $sql = "UPDATE friends SET title = :title, url = :url, comment = :comment WHERE id = :id;";
        $result = $this->db->query($sql, [
            ':title' => $data['title'],
            ':url' => $data['url'],
            ':comment' => $data['comment'],
            ':id' => $id
        ]);

        return $result ? $result : false;
    }

    public function delete(int $id): object|bool
    {
        $sql = "DELETE FROM friends WHERE id = :id;";
        $result = $this->db->query($sql, [':id' => $id]);

        return $result ? $result : false;
    }
}
