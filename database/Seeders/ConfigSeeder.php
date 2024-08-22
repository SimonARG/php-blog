<?php

namespace Database\Seeders;

use Database\Seeders\Seeder;

class ConfigSeeder extends Seeder
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $sql = "INSERT INTO config (title, icon, bg_color, text_color, panel_color, panel_hover, panel_active, text_dim, info) VALUES (:title, :icon, :bg_color, :text_color, :panel_color, :panel_hover, :panel_active, :text_dim, :info)";
        $this->db->query($sql, [
            ':title' => 'BLOG',
            ':icon' => 'favicon.png',
            ':bg_color' => 'blue',
            ':text_color' => 'white',
            ':panel_color' => 'rgba(0, 0, 0, 0.555)',
            ':panel_hover' => 'rgba(146, 146, 146, 0.24)',
            ':panel_active' => 'rgba(175, 175, 175, 0.24)',
            ':text_dim' => 'rgb(196, 196, 196)',
            ':info' => '...'
        ]);
    }
}
