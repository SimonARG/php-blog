<?php

namespace App\Seeders;

use App\Seeders\Seeder;

class ConfigSeeder extends Seeder
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $sql = "INSERT INTO config (title, icon, bg_color, text_color, panel_color, info) VALUES (:title, :icon, :bg_color, :text_color, :panel_color, :info)";
        $this->db->query($sql, [
            ':title' => 'BLOG',
            ':icon' => 'favicon.png',
            ':bg_color' => 'blue',
            ':text_color' => 'white',
            ':panel_color' => 'rgba(0, 0, 0, 0.555)',
            ':info' => '...',
        ]);
    }
}