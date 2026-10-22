
<?php

class Router
{
    public function dispatch(): void
    {
        $url = trim($_GET['url'] ?? '', '/');
        $segments = $url === '' ? [] : explode('/', $url);

        $resource = $segments[0] ?? '';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        try {
            if ($resource === 'login') {
                if ($method !== 'POST') {
                    header('Allow: POST');

                    Response::json(
                        405,
                        false,
                        'Method tidak diizinkan'
                    );
                }

                (new AuthController())->login();
                return;
            }

            if ($resource === 'mahasiswa') {
                $controller = new MahasiswaController();

                if ($method === 'GET' && count($segments) === 1) {
                    $controller->index();
                    return;
                }

                if ($method === 'POST' && count($segments) === 1) {
                    $controller->store();
                    return;
                }

                if (count($segments) === 2 && ctype_digit($segments[1])) {
                    $id = (int) $segments[1];

                    if ($method === 'PUT') {
                        $controller->update($id);
                        return;
                    }

                    if ($method === 'DELETE') {
                        $controller->destroy($id);
                        return;
                    }
                }

                header('Allow: GET, POST, PUT, DELETE');

                Response::json(
                    405,
                    false,
                    'Method tidak diizinkan atau endpoint belum tersedia'
                );
            }

            Response::json(
                404,
                false,
                'Endpoint tidak ditemukan'
            );
        } catch (Throwable $e) {
            error_log('Router error: ' . $e->getMessage());

            Response::json(
                500,
                false,
                'Terjadi kesalahan pada server'
            );
        }
    }
}
