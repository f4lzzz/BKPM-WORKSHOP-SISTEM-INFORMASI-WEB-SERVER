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

        if (
            empty($data['username']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            Response::error('Username, email, dan password wajib diisi', 400);
        }

        $query = "SELECT id FROM users
                  WHERE username = :username OR email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email']
        ]);

        if ($stmt->fetch()) {
            Response::error('Username atau email sudah digunakan', 409);
        }

        $password = password_hash(
            $data['password'],
            PASSWORD_DEFAULT
        );

        $query = "INSERT INTO users
                  (username, email, password)
                  VALUES (:username, :email, :password)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $password
        ]);

        Response::success(
            'Registrasi berhasil',
            [
                'id' => $this->conn->lastInsertId(),
                'username' => $data['username'],
                'email' => $data['email']
            ],
            201
        );
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            empty($data['username']) ||
            empty($data['password'])
        ) {
            Response::error('Username dan password wajib diisi', 400);
        }

        $query = "SELECT * FROM users WHERE username = :username";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':username' => $data['username']
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            !$user ||
            !password_verify($data['password'], $user['password'])
        ) {
            Response::error('Username atau password salah', 401);
        }

        $token = Auth::generateToken($user);

        Response::success(
            'Login berhasil',
            [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]
        );
    }
}
