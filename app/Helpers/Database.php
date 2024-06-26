<?php

namespace App\Helpers;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct($config)
    {
        try {
            $this->pdo = new PDO($config['dsn'], $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r($config);
            die("Could not connect to the database: " . $e->getMessage());
        }
    }

    public function query($sql, $params = [], $types = [])
    {
        $stmt = $this->pdo->prepare($sql);
        
        // Bind parameters with specified types
        foreach ($params as $key => $value) {
            if (isset($types[$key])) {
                $type = isset($types[$key]) ? $types[$key] : PDO::PARAM_INT;
                $stmt->bindValue($key, $value, $type);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        $stmt->execute();
        return $stmt;
    }

    public function fetchAll($sql, $params = [], $types = [])
    {
        $stmt = $this->query($sql, $params, $types);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch($sql, $params = [], $types = [])
    {
        $stmt = $this->query($sql, $params, $types);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}