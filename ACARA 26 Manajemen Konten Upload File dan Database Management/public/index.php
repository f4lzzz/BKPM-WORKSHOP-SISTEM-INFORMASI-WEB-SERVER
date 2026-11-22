
<?php

// Konfigurasi CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Menangani preflight request
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Memanggil seluruh file
require_once __DIR__ . "/../app/config/Database.php";

require_once __DIR__ . "/../app/core/Response.php";
require_once __DIR__ . "/../app/core/Router.php";
require_once __DIR__ . "/../app/core/Auth.php";

require_once __DIR__ . "/../app/models/User.php";
require_once __DIR__ . "/../app/models/Mahasiswa.php";

require_once __DIR__ . "/../app/controllers/AuthController.php";
require_once __DIR__ . "/../app/controllers/MahasiswaController.php";

// Koneksi database
$database = new Database();
$db = $database->getConnection();

// Inisialisasi controller
$authController = new AuthController($db);
$mahasiswaController = new MahasiswaController($db);

// Inisialisasi router
$router = new Router();

// Endpoint authentication
$router->add("POST", "/register", [$authController, "register"]);
$router->add("POST", "/login", [$authController, "login"]);
$router->add("POST", "/logout", [$authController, "logout"]);

// Endpoint mahasiswa
$router->add("GET", "/mahasiswa", [$mahasiswaController, "index"]);
$router->add("POST", "/mahasiswa", [$mahasiswaController, "store"]);
$router->add("POST", "/mahasiswa/update", [$mahasiswaController, "update"]);
$router->add("PUT", "/mahasiswa", [$mahasiswaController, "update"]);
$router->add("DELETE", "/mahasiswa", [$mahasiswaController, "destroy"]);

// Menjalankan router
$router->dispatch();