<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/core/autoload.php';

try {
    $router = new Router();
    $router->dispatch();
} catch (Throwable $e) {
    error_log('Application error: ' . $e->getMessage());

    Response::json(
        500,
        false,
        'Terjadi kesalahan pada server'
    );
}
