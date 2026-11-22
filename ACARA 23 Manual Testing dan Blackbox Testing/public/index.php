<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

require_once __DIR__ . "/../app/config/Database.php";
require_once __DIR__ . "/../app/core/Response.php";
require_once __DIR__ . "/../app/core/Router.php";
require_once __DIR__ . "/../app/core/Auth.php";
require_once __DIR__ . "/../app/models/Mahasiswa.php";
require_once __DIR__ . "/../app/controllers/AuthController.php";
require_once __DIR__ . "/../app/controllers/MahasiswaController.php";

$database = new Database();
$db = $database->getConnection();

$authController = new AuthController($db);
$mahasiswaController = new MahasiswaController($db);

$router = new Router();

// Authentication
$router->add("POST", "/register", [$authController, "register"]);
$router->add("POST", "/login", [$authController, "login"]);

// Mahasiswa
$router->add("GET", "/mahasiswa", [$mahasiswaController, "index"]);
$router->add("POST", "/mahasiswa", [$mahasiswaController, "store"]);
$router->add("PUT", "/mahasiswa/{id}", [$mahasiswaController, "update"]);
$router->add("DELETE", "/mahasiswa/{id}", [$mahasiswaController, "destroy"]);

$router->dispatch();
