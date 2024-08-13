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

    public function query(string $sql, array $params = [], array $types = []) : object|int
    {
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            if (isset($types[$key])) {
                $type = isset($types[$key]) ? $types[$key] : PDO::PARAM_INT;
                $stmt->bindValue($key, $value, $type);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            // print_r($e);
            return 0;
        }

        return $stmt;
    }

    public function fetchAll(string $sql, array $params = [], array $types = []) : array|false
    {
        $stmt = $this->query($sql, $params, $types);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch(string $sql, array $params = [], array $types = []) : array|false
    {
        $stmt = $this->query($sql, $params, $types);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}