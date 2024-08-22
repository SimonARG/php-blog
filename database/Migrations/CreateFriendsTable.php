<?php

namespace Database\Migrations;

class CreateFriendsTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS friends (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255),
            url VARCHAR(255),
            comment VARCHAR(255) DEFAULT NULL
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS friends";
        $db->query($sql);
    }
}