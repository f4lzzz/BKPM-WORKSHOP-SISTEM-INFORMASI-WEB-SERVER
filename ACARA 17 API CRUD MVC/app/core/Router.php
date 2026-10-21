
<?php

class Router
{
    private array $map = [
        'mahasiswa' => 'MahasiswaController'
    ];

    public function dispatch(): void
    {
        $uriPath = parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        ) ?? '/';

        // Ambil bagian URL setelah folder public/
        $marker = '/public/';
        $position = strpos($uriPath, $marker);

        if ($position !== false) {
            $uriPath = substr(
                $uriPath,
                $position + strlen($marker)
            );
        } else {
            $uriPath = trim($uriPath, '/');
        }

        $resource = trim($uriPath, '/');

        header('Content-Type: application/json; charset=utf-8');

        if (!isset($this->map[$resource])) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Endpoint tidak ditemukan',
                'path' => $resource
            ]);

            return;
        }

        $controllerName = $this->map[$resource];
        $controller = new $controllerName();

        switch ($_SERVER['REQUEST_METHOD'] ?? 'GET') {
            case 'GET':
                $controller->index();
                break;

            case 'POST':
                $controller->store();
                break;

            case 'PUT':
                $controller->update();
                break;

            case 'DELETE':
                $controller->destroy();
                break;

            default:
                header('Allow: GET, POST, PUT, DELETE');
                http_response_code(405);

                echo json_encode([
                    'success' => false,
                    'message' => 'Method tidak diizinkan'
                ]);
        }
    }
}
