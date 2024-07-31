<?php

namespace App\Models;

use App\Models\Model;

class Blog extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBlogConfig() : array|int
    {
        $sql = "SELECT * FROM config;";
        $result = $this->db->fetch($sql);

        return $result ? $result : 0;
    }

    public function getContacts() : array|int
    {
        $sql = "SELECT * FROM contact;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }

    public function getFriends() : array|int
    {
        $sql = "SELECT * FROM friends;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }

    public function getLinks() : array|int
    {
        $sql = "SELECT * FROM links;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }
}