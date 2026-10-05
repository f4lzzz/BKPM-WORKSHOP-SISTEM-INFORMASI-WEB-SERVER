<?php

class BaseController
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        ob_start();

        require __DIR__ . '/../Views/' . $view . '.php';

        $content = ob_get_clean();

        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}