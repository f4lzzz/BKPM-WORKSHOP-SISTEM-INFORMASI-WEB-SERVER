<?php

require_once __DIR__ . '/Database.php';

class BaseModel
{
    protected PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    protected function getConnection(): PDO
    {
        return $this->pdo;
    }
}