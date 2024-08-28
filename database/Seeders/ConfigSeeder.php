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
            ':bg_color' => '#4F4F4F',
            ':text_color' => '#FF3DF9',
            ':panel_color' => '#000000',
            ':panel_hover' => '#929292',
            ':panel_active' => '#FEB4F4',
            ':text_dim' => '#FEB4F4',
            ':info' => '...',
            ':main_scrollbar' => '#FF3DF9',
            ':popup_bg' => '#000000'
        ]);
    }
}
