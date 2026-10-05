<?php
class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get(string $path, callable $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch(): void
{
    $method = $_SERVER['REQUEST_METHOD'];

    // Ambil path dan ubah %20 menjadi spasi
    $path = rawurldecode(
        parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
    );

    // Ambil lokasi folder public
    $base = rawurldecode(
        str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']))
    );

    // Hilangkan lokasi folder public dari path
    if ($base !== '/' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base));
    }

    // Rapikan path
    $path = '/' . trim($path, '/');

    if ($path === '//') {
        $path = '/';
    }

    $callback = $this->routes[$method][$path] ?? null;

    if ($callback !== null) {
        call_user_func($callback);
        return;
    }

    http_response_code(404);
    echo '404 - Halaman tidak ditemukan. Path: '
        . htmlspecialchars($path);
}
}