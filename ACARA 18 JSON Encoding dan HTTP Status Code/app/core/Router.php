
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

        // Ambil bagian URL setelah folder public/.
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

        // Tangani endpoint yang tidak tersedia.
        if (!isset($this->map[$resource])) {
            Response::json(
                404,
                false,
                'Endpoint tidak ditemukan',
                [
                    'path' => $resource
                ]
            );
        }

        $controllerName = $this->map[$resource];
        $controller = new $controllerName();

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        switch ($method) {
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

                Response::json(
                    405,
                    false,
                    'Method tidak diizinkan'
                );
        }
    }
}
