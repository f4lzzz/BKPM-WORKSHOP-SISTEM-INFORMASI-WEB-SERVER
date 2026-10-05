<?php

session_start();

require_once __DIR__ . '/../app/Core/BaseController.php';
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Models/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Services/MahasiswaService.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$baseUrl = str_replace('/index.php', '', $scriptName);

define('BASE_URL', $baseUrl);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rawurldecode($requestUri);

$basePath = rawurldecode(BASE_URL);

if ($basePath !== '/' && str_starts_with($requestUri, $basePath)) {
    $path = substr($requestUri, strlen($basePath));
} else {
    $path = $requestUri;
}

$path = trim($path, '/');

$method = $_SERVER['REQUEST_METHOD'];

$controller = new MahasiswaController();

try {

    if ($path === '' && $method === 'GET') {

        $controller->index();

    } elseif ($path === 'mahasiswa' && $method === 'GET') {

        $controller->index();

    } elseif ($path === 'mahasiswa' && $method === 'POST') {

        $controller->store();

    } elseif ($path === 'mahasiswa/create' && $method === 'GET') {

        $controller->create();

    } elseif (
        preg_match('#^mahasiswa/edit$#', $path)
        && $method === 'GET'
    ) {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            throw new Exception('ID mahasiswa tidak valid.');
        }

        $controller->edit($id);

    } elseif (
        preg_match('#^mahasiswa/update/([0-9]+)$#', $path, $matches)
        && $method === 'POST'
    ) {

        $controller->update((int) $matches[1]);

    } elseif (
        preg_match('#^mahasiswa/delete/([0-9]+)$#', $path, $matches)
        && $method === 'POST'
    ) {

        $controller->delete((int) $matches[1]);

    } else {

        http_response_code(404);

        echo 'Halaman tidak ditemukan.';
    }

} catch (Exception $e) {

    error_log(
        date('Y-m-d H:i:s') .
        ' - ' .
        $e->getMessage() .
        PHP_EOL,
        3,
        __DIR__ . '/../storage/logs/app.log'
    );

    $_SESSION['flash'] = [
        'type' => 'danger',
        'message' => 'Terjadi kesalahan pada aplikasi.'
    ];

    header('Location: ' . BASE_URL . '/mahasiswa');
    exit;
}