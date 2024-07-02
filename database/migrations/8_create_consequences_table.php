<?php

class CreateConsequencesTable
{
    public function up()
    {
        $db = $GLOBALS['db'];
        $sql = "CREATE TABLE IF NOT EXISTS consequences (
            id INT AUTO_INCREMENT PRIMARY KEY,
            consequence varchar(255)
        )";

        $db->query($sql);
    }

    public function down()
    {
        $db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS consequences";
        $db->query($sql);
    }
}