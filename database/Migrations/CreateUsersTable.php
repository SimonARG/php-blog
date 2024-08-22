<?php

namespace Database\Migrations;

class CreateUsersTable extends Migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db = $GLOBALS['db'];
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

        $this->db->query($sql);
    }

    public function down()
    {
        $this->db = $GLOBALS['db'];
        $sql = "DROP TABLE IF EXISTS reports";
        $this->db->query($sql);
        $sql = "DROP TABLE IF EXISTS mod_actions";
        $this->db->query($sql);
        $sql = "DROP TABLE IF EXISTS reported_resources";
        $this->db->query($sql);
        $sql = "DROP TABLE IF EXISTS saved_posts";
        $this->db->query($sql);
        $sql = "DROP TABLE IF EXISTS comments";
        $this->db->query($sql);
        $sql = "DROP TABLE IF EXISTS posts";
        $this->db->query($sql);
        $sql = "DROP TABLE IF EXISTS role_user";
        $this->db->query($sql);
        $sql = "DROP TABLE IF EXISTS users";
        $this->db->query($sql);
    }
}