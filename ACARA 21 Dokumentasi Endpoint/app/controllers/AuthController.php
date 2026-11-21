<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Response.php';

class AuthController
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($this->conn === null) {
            Response::json(500, false, 'Koneksi database gagal');
        }
    }

    public function register(): void
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!is_array($input)) {
                Response::json(400, false, 'Format JSON tidak valid');
            }

            $username = trim($input['username'] ?? '');
            $email = trim($input['email'] ?? '');
            $password = $input['password'] ?? '';

            if ($username === '' || $email === '' || $password === '') {
                Response::json(400, false, 'Username, email, dan password wajib diisi');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Response::json(400, false, 'Format email tidak valid');
            }

            if (strlen($password) < 8) {
                Response::json(400, false, 'Password minimal 8 karakter');
            }

            $check = $this->conn->prepare(
                'SELECT id FROM users WHERE username = :username OR email = :email'
            );

            $check->execute([
                ':username' => $username,
                ':email' => $email
            ]);

            if ($check->fetch()) {
                Response::json(409, false, 'Username atau email sudah digunakan');
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare(
                'INSERT INTO users (username, email, password, role)
                 VALUES (:username, :email, :password, :role)'
            );

            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':role' => 'user'
            ]);

            Response::json(201, true, 'Registrasi berhasil', [
                'id' => $this->conn->lastInsertId(),
                'username' => $username,
                'email' => $email,
                'role' => 'user'
            ]);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }

    public function login(): void
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!is_array($input)) {
                Response::json(400, false, 'Format JSON tidak valid');
            }

            $username = trim($input['username'] ?? '');
            $password = $input['password'] ?? '';

            if ($username === '' || $password === '') {
                Response::json(400, false, 'Username dan password wajib diisi');
            }

            $stmt = $this->conn->prepare(
                'SELECT id, username, email, password, role
                 FROM users WHERE username = :username LIMIT 1'
            );

            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                Response::json(401, false, 'Username atau password salah');
            }

            $token = Auth::generateToken([
                'id' => (int) $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]);

            Response::json(200, true, 'Login berhasil', [
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'role' => $user['role']
            ]);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}
