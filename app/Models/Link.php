<?php

namespace App\Models;

use App\Models\Model;

class Link extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(): array|int
    {
        $sql = "SELECT * FROM links;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }

    public function store(array $data): bool
    {
        $sql = "INSERT INTO links (title, url) VALUES (:title, :url);";
        $result = $this->db->query($sql, [
            ':title' => $data['title'],
            ':url' => $data['url']
        ]);

        return $result ? true : false;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE links SET title = :title, url = :url WHERE id = :id;";
        $result = $this->db->query($sql, [
            ':title' => $data['title'],
            ':url' => $data['url'],
            ':id' => $id
        ]);

        return $result ? true : false;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM links WHERE id = :id;";
        $result = $this->db->query($sql, [':id' => $id]);

        return $result ? true : false;
    }
}
