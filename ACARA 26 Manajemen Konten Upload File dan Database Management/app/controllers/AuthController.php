
<?php

class AuthController
{
    private $user;

    public function __construct($db)
    {
        $this->user = new User($db);
    }

    public function register()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            empty($data["username"]) ||
            empty($data["email"]) ||
            empty($data["password"])
        ) {
            Response::error(400, "Username, email, dan password wajib diisi");
        }

        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            Response::error(400, "Format email tidak valid");
        }

        if (strlen($data["password"]) < 6) {
            Response::error(400, "Password minimal 6 karakter");
        }

        if ($this->user->findByUsername($data["username"])) {
            Response::error(409, "Username sudah terdaftar");
        }

        try {
            $this->user->create(
                $data["username"],
                $data["email"],
                $data["password"],
                "user"
            );

            Response::success("Registrasi berhasil");
        } catch (PDOException $e) {
            Response::error(409, "Email sudah terdaftar atau data tidak valid");
        }
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data["username"]) || empty($data["password"])) {
            Response::error(400, "Username dan password wajib diisi");
        }

        $user = $this->user->findByUsername($data["username"]);

        if (!$user || !password_verify($data["password"], $user["password"])) {
            Response::error(401, "Username atau password salah");
        }

        $token = Auth::generateToken($user);

        Response::success("Login berhasil", [
            "token" => $token,
            "role" => $user["role"],
            "username" => $user["username"]
        ]);
    }

    public function logout()
    {
        Auth::requireAuth();

        Response::success("Logout berhasil. Silakan hapus token di sisi frontend.");
    }
}
