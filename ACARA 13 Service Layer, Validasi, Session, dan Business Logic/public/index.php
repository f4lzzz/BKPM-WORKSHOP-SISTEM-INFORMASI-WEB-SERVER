<?php

session_start();

require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

/*
|--------------------------------------------------------------------------
| BASE URL
|--------------------------------------------------------------------------
*/

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$baseUrl = str_replace('/index.php', '', $scriptName);

define('BASE_URL', $baseUrl);

/*
|--------------------------------------------------------------------------
| REQUEST PATH
|--------------------------------------------------------------------------
*/

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rawurldecode($requestUri);
$basePath = rawurldecode(BASE_URL);

/*
|--------------------------------------------------------------------------
| Ambil path setelah /public
|--------------------------------------------------------------------------
*/

if ($basePath !== '/' && str_starts_with($requestUri, $basePath)) {
    $path = substr($requestUri, strlen($basePath));
} else {
    $path = $requestUri;
}

$path = trim($path, '/');

$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
*/

$controller = new MahasiswaController();

/*
|--------------------------------------------------------------------------
| Routing
|--------------------------------------------------------------------------
*/

if ($path === '' && $method === 'GET') {

    $controller->index();

} elseif ($path === 'mahasiswa' && $method === 'GET') {

    $controller->index();

} elseif ($path === 'mahasiswa' && $method === 'POST') {

    $controller->store();

} elseif ($path === 'mahasiswa/create' && $method === 'GET') {

    $controller->create();

} elseif (
    preg_match('#^mahasiswa/edit/([0-9]+)$#', $path, $matches)
    && $method === 'GET'
) {

    $controller->edit((int) $matches[1]);

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

    echo '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>404 - Halaman Tidak Ditemukan</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f8f9fa;
                padding: 50px;
                text-align: center;
            }

            .box {
                background: white;
                padding: 30px;
                border-radius: 10px;
                max-width: 500px;
                margin: auto;
                box-shadow: 0 2px 10px rgba(0,0,0,.1);
            }

            h1 {
                color: #dc3545;
            }
        </style>
    </head>
    <body>

        <div class="box">
            <h1>404</h1>
            <p>Halaman tidak ditemukan.</p>
            <a href="' . BASE_URL . '/mahasiswa">
                Kembali ke Data Mahasiswa
            </a>
        </div>

    </body>
    </html>
    ';
}