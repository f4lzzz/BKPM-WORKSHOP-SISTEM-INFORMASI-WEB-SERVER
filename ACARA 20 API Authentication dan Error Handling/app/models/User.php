<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function findByUsername(string $username): array|false
    {
        $sql = 'SELECT id, username, email, password, role
                FROM users
                WHERE username = :username
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username' => $username
        ]);

        return $stmt->fetch();
    }
}
