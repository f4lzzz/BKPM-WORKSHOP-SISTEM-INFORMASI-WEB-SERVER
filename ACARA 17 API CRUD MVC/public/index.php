
<?php

require_once __DIR__ . '/../app/core/autoload.php';

try {
    (new Router())->dispatch();
} catch (Throwable $e) {
    error_log($e->getMessage());

    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server'
    ]);
}
