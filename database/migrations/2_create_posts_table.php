<?php

class CreatePostsTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            subtitle VARCHAR(255),
            thumb VARCHAR(255),
            body TEXT NOT NULL,
            user_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            deleted_at DATETIME DEFAULT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS users";
        $db->query($sql);
    }
}