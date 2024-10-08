<?php

namespace Database\Migrations;

class CreateConfigTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS config (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255),
            icon VARCHAR(255),
            bg_image VARCHAR(255) DEFAULT NULL,
            bg_color VARCHAR(60) DEFAULT NULL,
            text_color VARCHAR(60),
            panel_color VARCHAR(60),
            panel_hover VARCHAR(60),
            panel_active VARCHAR(60),
            text_dim VARCHAR(60),
            main_scrollbar VARCHAR(60),
            popup_bg VARCHAR(60),
            info VARCHAR(4000)
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS config";
        $db->query($sql);
    }
}