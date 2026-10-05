
<?php
session_start();

ini_set('display_errors', '1');
error_reporting(E_ALL);

// BASE URL otomatis
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$baseUrl = rtrim(dirname($scriptName), '/');

if ($baseUrl === '.') {
    $baseUrl = '';
}

define('BASE_URL', $baseUrl);

// Core
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Router.php';

// Models
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Models/Prodi.php';
require_once __DIR__ . '/../app/Models/Matakuliah.php';

// Controllers
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';

// Router
$router = new Router();

// Controller
$mahasiswaController = new MahasiswaController();
$prodiController = new ProdiController();
$matakuliahController = new MatakuliahController();

// Beranda
$router->get('/', function () {
    header('Location: ' . BASE_URL . '/mahasiswa');
    exit;
});

// ==========================
// MAHASISWA
// ==========================
$router->get('/mahasiswa', [$mahasiswaController, 'index']);
$router->get('/mahasiswa/create', [$mahasiswaController, 'create']);
$router->post('/mahasiswa/store', [$mahasiswaController, 'store']);
$router->get('/mahasiswa/edit', [$mahasiswaController, 'edit']);
$router->post('/mahasiswa/update', [$mahasiswaController, 'update']);
$router->post('/mahasiswa/destroy', [$mahasiswaController, 'delete']);

// ==========================
// PRODI
// ==========================
$router->get('/prodi', [$prodiController, 'index']);
$router->get('/prodi/create', [$prodiController, 'create']);
$router->post('/prodi/store', [$prodiController, 'store']);
$router->get('/prodi/edit', [$prodiController, 'edit']);
$router->post('/prodi/update', [$prodiController, 'update']);
$router->post('/prodi/destroy', [$prodiController, 'delete']);

// ==========================
// MATA KULIAH
// ==========================
$router->get('/matakuliah', [$matakuliahController, 'index']);
$router->get('/matakuliah/create', [$matakuliahController, 'create']);
$router->post('/matakuliah/store', [$matakuliahController, 'store']);
$router->get('/matakuliah/edit', [$matakuliahController, 'edit']);
$router->post('/matakuliah/update', [$matakuliahController, 'update']);
$router->post('/matakuliah/destroy', [$matakuliahController, 'delete']);

// Jalankan
$router->dispatch();