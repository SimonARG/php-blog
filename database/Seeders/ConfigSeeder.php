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
        $sql = "INSERT INTO config (title, icon, bg_color, text_color, panel_color, panel_hover, panel_active, text_dim, info, main_scrollbar, popup_bg) VALUES (:title, :icon, :bg_color, :text_color, :panel_color, :panel_hover, :panel_active, :text_dim, :info, :main_scrollbar, :popup_bg);";
        $this->db->query($sql, [
            ':title' => 'BLOG',
            ':icon' => 'favicon.png',
            ':bg_color' => 'rgb(79, 79, 79)',
            ':text_color' => 'rgb(255, 61, 249)',
            ':panel_color' => '#000000',
            ':panel_hover' => 'rgba(146, 146, 146, 0.24)',
            ':panel_active' => 'rgba(254, 180, 244, 0.24)',
            ':text_dim' => 'rgb(254, 180, 244)',
            ':info' => '...',
            ':main_scrollbar' => 'rgb(255, 61, 249)',
            ':popup_bg' => 'rgb(0, 0, 0)'
        ]);
    }
}
