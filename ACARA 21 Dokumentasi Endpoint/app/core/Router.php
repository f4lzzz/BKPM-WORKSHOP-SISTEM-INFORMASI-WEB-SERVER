<?php

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/MahasiswaController.php';
require_once __DIR__ . '/Response.php';

class Router
{
    public function dispatch(): void
    {
        try {
            $url = trim($_GET['url'] ?? '', '/');
            $segments = $url === '' ? [] : explode('/', $url);
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

            $resource = $segments[0] ?? '';

            if ($resource === 'register' || $resource === 'login') {
                if ($method !== 'POST' || count($segments) !== 1) {
                    header('Allow: POST');
                    Response::json(405, false, 'Method tidak diizinkan');
                }

                $controller = new AuthController();

                if ($resource === 'register') {
                    $controller->register();
                }

                $controller->login();
                return;
            }

            if ($resource === 'mahasiswa') {
                $controller = new MahasiswaController();

                if (count($segments) === 1) {
                    if ($method === 'GET') {
                        $controller->index();
                    }

                    if ($method === 'POST') {
                        $controller->store();
                    }
                }

                if (count($segments) === 2 && ctype_digit($segments[1])) {
                    $id = (int) $segments[1];

                    if ($method === 'PUT') {
                        $controller->update($id);
                    }

                    if ($method === 'DELETE') {
                        $controller->destroy($id);
                    }
                }

                header('Allow: GET, POST, PUT, DELETE');
                Response::json(
                    405,
                    false,
                    'Method tidak diizinkan atau endpoint belum tersedia'
                );
            }

            Response::json(404, false, 'Endpoint tidak ditemukan');

        } catch (Throwable $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}
