
<?php

spl_autoload_register(function (string $class): void {
    $folders = [
        'config',
        'core',
        'models',
        'controllers'
    ];

    foreach ($folders as $folder) {
        $path = __DIR__ . "/../{$folder}/{$class}.php";

        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
