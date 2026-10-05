
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Ambil lokasi folder public
$scriptName = rawurldecode(
    str_replace('\\', '/', $_SERVER['SCRIPT_NAME'])
);
$basePath = rtrim(dirname($scriptName), '/');

// Jika berada di root, gunakan string kosong
if ($basePath === '/' || $basePath === '.') {
    $basePath = '';
}

define('BASE_URL', $basePath);

// Load file aplikasi
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Models/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

// Inisialisasi aplikasi
$database = new Database();
$repository = new MahasiswaRepository($database);
$controller = new MahasiswaController($repository);

// Ambil URL dan hilangkan query string
$requestPath = rawurldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Hapus base path dari URL
if (
    $basePath !== '' &&
    strpos($requestPath, $basePath) === 0
) {
    $requestPath = substr($requestPath, strlen($basePath));
}

// Normalisasi route
$route = '/' . trim($requestPath, '/');
$method = $_SERVER['REQUEST_METHOD'];

// Routing
if ($route === '/' && $method === 'GET') {
    $controller->index();

} elseif ($route === '/mahasiswa/create' && $method === 'GET') {
    $controller->create();

} elseif ($route === '/mahasiswa/store' && $method === 'POST') {
    $controller->store();

} elseif ($route === '/mahasiswa/edit' && $method === 'GET') {
    $controller->edit();

} elseif ($route === '/mahasiswa/update' && $method === 'POST') {
    $controller->update();

} elseif ($route === '/mahasiswa/delete' && $method === 'POST') {
    $controller->delete();

} else {
    http_response_code(404);
    echo '<h2>Halaman tidak ditemukan.</h2>';
    echo '<p>Route terbaca: ' . htmlspecialchars($route) . '</p>';
    echo '<p>Base path: ' . htmlspecialchars($basePath) . '</p>';
    echo '<p>Request URI: ' . htmlspecialchars($_SERVER['REQUEST_URI']) . '</p>';
}