<?php

class CreateOtherBlogsTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS other_blogs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255),
            URL VARCHAR(255),
            comment VARCHAR(255) DEFAULT NULL,
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS other_blogs";
        $db->query($sql);
    }
}