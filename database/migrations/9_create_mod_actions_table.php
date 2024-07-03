<?php

class CreateModActionsTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS mod_actions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reviewer_id INT NOT NULL,
            motive VARCHAR(255),
            resource_id INT,
            consequence_id INT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (reviewer_id) REFERENCES users(id),
            FOREIGN KEY (consequence_id) REFERENCES consequences(id),
            FOREIGN KEY (resource_id) REFERENCES reported_resources(id)
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS mod_actions";
        $db->query($sql);
    }
}