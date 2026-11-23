
<?php

class User
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findByUsername($username)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($username, $email, $password, $role = "user")
    {
        $query = "INSERT INTO " . $this->table . "
                  (username, email, password, role)
                  VALUES (:username, :email, :password, :role)";

        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();
    }
}