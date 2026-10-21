
<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/core/autoload.php';

$router = new Router();
$router->dispatch();
