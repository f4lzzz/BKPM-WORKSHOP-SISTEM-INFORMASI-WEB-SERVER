
<?php

class Router
{
    private $routes = [];

    public function add($method, $path, $callback)
    {
        $this->routes[] = [
            "method" => strtoupper($method),
            "path" => $path,
            "callback" => $callback
        ];
    }

    public function dispatch()
    {
        $method = strtoupper($_SERVER["REQUEST_METHOD"]);

        $requestPath = rawurldecode(
            parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)
        );

        // Mengambil bagian URL setelah folder public
        $publicPosition = strrpos($requestPath, "/public");

        if ($publicPosition !== false) {
            $requestPath = substr(
                $requestPath,
                $publicPosition + strlen("/public")
            );
        }

        $uri = "/" . trim($requestPath, "/");

        foreach ($this->routes as $route) {
            if (
                $route["method"] === $method &&
                $route["path"] === $uri
            ) {
                call_user_func($route["callback"]);
                return;
            }
        }

        Response::error(
            404,
            "Endpoint tidak ditemukan: " . $method . " " . $uri
        );
    }
}
