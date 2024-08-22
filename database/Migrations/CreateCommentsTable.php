<?php

namespace Database\Migrations;

class CreateCommentsTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            body TEXT NOT NULL,
            user_id INT NOT NULL,
            post_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS comments";
        $db->query($sql);
    }
}