<?php
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
require_once __DIR__ . "/../app/models/Mahasiswa.php";
require_once __DIR__ . "/../app/controllers/MahasiswaController.php";

$database = new Database();
$db = $database->getConnection();

$router = new Router();

$mahasiswaController = new MahasiswaController($db);

$router->add("GET", "/mahasiswa", function () use ($mahasiswaController) {
    $mahasiswaController->index();
});

$router->dispatch();

