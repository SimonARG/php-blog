<?php

class CreateReportsTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS reports (
            id INT AUTO_INCREMENT PRIMARY KEY,
            resource_id INT,
            comment VARCHAR(255),
            reported_by INT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            reviewed BIT(1) DEFAULT 0,
            reviewed_by INT,
            updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (resource_id) REFERENCES reported_resources(id),
            FOREIGN KEY (reviewed_by) REFERENCES users(id),
            FOREIGN KEY (reported_by) REFERENCES users(id)
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];

        $sql = "DROP TABLE IF EXISTS reports";
        $db->query($sql);
    }
}