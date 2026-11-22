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

        $uri = rawurldecode(
            parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)
        );

        $scriptName = rawurldecode($_SERVER["SCRIPT_NAME"]);
        $basePath = rtrim(str_replace("\\", "/", dirname($scriptName)), "/");

        if ($basePath !== "" && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = "/" . trim($uri, "/");

        foreach ($this->routes as $route) {
            if ($route["method"] !== $method) {
                continue;
            }

            $pattern = preg_replace(
                "/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/",
                "([0-9]+)",
                $route["path"]
            );

            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                call_user_func_array($route["callback"], $matches);
                return;
            }
        }

        Response::error("Endpoint tidak ditemukan", 404);
    }
}
