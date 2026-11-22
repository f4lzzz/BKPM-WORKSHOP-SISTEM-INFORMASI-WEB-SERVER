<?php

class AuthController
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $username = trim($data["username"] ?? "");
        $email = trim($data["email"] ?? "");
        $password = $data["password"] ?? "";

        if ($username === "" || $email === "" || $password === "") {
            Response::error("Username, email, dan password wajib diisi", 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::error("Format email tidak valid", 400);
        }

        $check = $this->conn->prepare(
            "SELECT id FROM users WHERE username = :username OR email = :email"
        );

        $check->execute([
            ":username" => $username,
            ":email" => $email
        ]);

        if ($check->fetch()) {
            Response::error("Username atau email sudah digunakan", 409);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password)
                  VALUES (:username, :email, :password)";

        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":password" => $hashedPassword
            ]);
        } catch (PDOException $e) {
            Response::error("Registrasi gagal", 500);
        }

        Response::success("Registrasi berhasil", [
            "id" => $this->conn->lastInsertId(),
            "username" => $username,
            "email" => $email
        ], 201);
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $username = trim($data["username"] ?? "");
        $password = $data["password"] ?? "";

        if ($username === "" || $password === "") {
            Response::error("Username dan password wajib diisi", 400);
        }

        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([":username" => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user["password"])) {
            Response::error("Username atau password salah", 401);
        }

        $token = Auth::generateToken($user);

        Response::success("Login berhasil", [
            "user" => [
                "id" => $user["id"],
                "username" => $user["username"],
                "email" => $user["email"],
                "role" => $user["role"]
            ],
            "token" => $token,
            "expires_in" => 3600
        ]);
    }
}
