<?php

class CreateReportedResourcesTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS reported_resources (
            id INT PRIMARY KEY AUTO_INCREMENT,
            post_id INT UNIQUE,
            comment_id INT UNIQUE,
            user_id INT UNIQUE,
            FOREIGN KEY (post_id) REFERENCES posts(id),
            FOREIGN KEY (comment_id) REFERENCES comments(id),
            FOREIGN KEY (user_id) REFERENCES users(id)
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS reported_resources";
        $db->query($sql);
    }
}