<?php

namespace App\Models;

use App\Models\Model;

class Contact extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getContacts(): array|int
    {
        $sql = "SELECT * FROM contact;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }

    public function store(array $data): object|bool
    {
        $sql = "INSERT INTO contact (title, url) VALUES (:title, :url);";
        $result = $this->db->query($sql, [
            ':title' => $data['title'],
            ':url' => $data['url']
        ]);

        return $result ? $result : 0;
    }

    public function update(int $id, array $data): object|bool
    {
        $sql = "UPDATE contact SET title = :title, url = :url WHERE id = :id;";
        $result = $this->db->query($sql, [
            ':title' => $data['title'],
            ':url' => $data['url'],
            ':id' => $id
        ]);

        return $result ? $result : false;
    }

    public function delete(int $id): object|bool
    {
        $sql = "DELETE FROM contact WHERE id = :id;";
        $result = $this->db->query($sql, [':id' => $id]);

        return $result ? $result : false;
    }
}
