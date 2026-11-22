<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/core/Response.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/models/Mahasiswa.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/MahasiswaController.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    Response::error('Koneksi database gagal', 500);
}

$authController = new AuthController($db);
$mahasiswaController = new MahasiswaController($db);

$router = new Router();

$router->post('/register', [$authController, 'register']);
$router->post('/login', [$authController, 'login']);

$router->get('/mahasiswa', [$mahasiswaController, 'index']);
$router->post('/mahasiswa', [$mahasiswaController, 'store']);
$router->put('/mahasiswa/{id}', [$mahasiswaController, 'update']);
$router->delete('/mahasiswa/{id}', [$mahasiswaController, 'destroy']);

$router->run();
