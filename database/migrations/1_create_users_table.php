<?php

class CreateUsersTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            avatar VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "ALTER TABLE mod_actions
                DROP FOREIGN KEY mod_actions_ibfk_4;";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS reports";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS mod_actions";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS reported_resources";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS saved_posts";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS comments";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS posts";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS role_user";
        $db->query($sql);
    }
}