
<?php

class AuthController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function register(): void
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!is_array($data)) {
            Response::json([
                'success' => false,
                'message' => 'Format JSON tidak valid'
            ], 400);
        }

        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            Response::json([
                'success' => false,
                'message' => 'Username, email, dan password wajib diisi'
            ], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json([
                'success' => false,
                'message' => 'Format email tidak valid'
            ], 400);
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $userId = $this->user->create([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword
            ]);

            Response::json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => [
                    'id' => $userId,
                    'username' => $username,
                    'email' => $email
                ]
            ], 201);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                Response::json([
                    'success' => false,
                    'message' => 'Username atau email sudah digunakan'
                ], 409);
            }

            Response::json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database'
            ], 500);
        }
    }
}
