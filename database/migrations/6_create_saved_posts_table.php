<?php

class CreateSavedPostsTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE saved_posts (
            user_id INTEGER NOT NULL,
            post_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id, post_id),
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (post_id) REFERENCES posts(id)
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS saved_posts";
    }
}