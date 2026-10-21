
<?php

spl_autoload_register(function (string $class): void {
    $directories = [
        __DIR__ . '/../config/',
        __DIR__ . '/../controllers/',
        __DIR__ . '/../core/',
        __DIR__ . '/../models/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
