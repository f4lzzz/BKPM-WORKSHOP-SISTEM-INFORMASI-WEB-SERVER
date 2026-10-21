
<?php

require_once __DIR__ . '/../app/core/autoload.php';

try {
    $router = new Router();
    $router->dispatch();
} catch (Throwable $e) {
    error_log(
        'Acara 18 API Error: ' . $e->getMessage()
    );

    Response::json(
        500,
        false,
        'Terjadi kesalahan pada server'
    );
}
