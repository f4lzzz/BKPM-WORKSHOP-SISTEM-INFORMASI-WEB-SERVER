
<?php

class Router
{
    public function dispatch(): void
    {
        $resource = trim($_GET['url'] ?? '', '/');
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $routes = [
            'mahasiswa' => 'MahasiswaController',
            'register' => 'AuthController'
        ];

        if (!isset($routes[$resource])) {
            Response::json([
                'success' => false,
                'message' => 'Endpoint tidak ditemukan'
            ], 404);
        }

        if ($resource === 'register' && $method !== 'POST') {
            Response::json([
                'success' => false,
                'message' => 'Method tidak diizinkan'
            ], 405);
        }

        if ($resource === 'mahasiswa' && $method !== 'GET') {
            Response::json([
                'success' => false,
                'message' => 'Method tidak diizinkan'
            ], 405);
        }

        $controllerName = $routes[$resource];
        $controller = new $controllerName();

        if ($resource === 'register') {
            $controller->register();
            return;
        }

        if ($resource === 'mahasiswa') {
            $controller->index();
            return;
        }
    }
}
