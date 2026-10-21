
<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO users (username, email, password)
                VALUES (:username, :email, :password)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $data['password']
        ]);

        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username): array|false
    {
        $sql = "SELECT id, username, email, password, role, created_at
                FROM users
                WHERE username = :username
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username' => $username
        ]);

        return $stmt->fetch();
    }
}
