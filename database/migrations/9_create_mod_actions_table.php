<?php

class CreateModActionsTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS mod_actions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            motive VARCHAR(255),
            motive_id INT,
            action_by INT,
            consequence_id INT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (action_by) REFERENCES users(id),
            FOREIGN KEY (consequence_id) REFERENCES consequences(id)
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