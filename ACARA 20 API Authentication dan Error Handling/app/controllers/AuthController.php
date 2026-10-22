<?php

class AuthController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function login(): void
    {
        try {
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);

            if (!is_array($data)) {
                Response::json(
                    400,
                    false,
                    'Format JSON tidak valid'
                );
            }

            $username = trim($data['username'] ?? '');
            $password = $data['password'] ?? '';

            if ($username === '' || !is_string($password) || $password === '') {
                Response::json(
                    400,
                    false,
                    'Username dan password wajib diisi'
                );
            }

            $user = $this->model->findByUsername($username);

            if (!$user || !password_verify($password, $user['password'])) {
                Response::json(
                    401,
                    false,
                    'Username atau password salah'
                );
            }

            $token = Auth::generateToken([
                'id' => (int) $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]);

            Response::json(
                200,
                true,
                'Login berhasil',
                [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                    'role' => $user['role']
                ]
            );
        } catch (Throwable $e) {
            error_log('Login gagal: ' . $e->getMessage());

            Response::json(
                500,
                false,
                'Terjadi kesalahan pada server'
            );
        }
    }
}
