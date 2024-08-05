<?php

namespace App\Models;

use App\Models\Model;

class Link extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getLinks() : array|int
    {
        $sql = "SELECT * FROM links;";
        $result = $this->db->fetchAll($sql);

        return $result ? $result : 0;
    }
}