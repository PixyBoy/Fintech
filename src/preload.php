<?php
$paths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
    __DIR__ . '/vendor/laravel/framework/src/Illuminate/Support/helpers.php',
    __DIR__ . '/bootstrap/cache/packages.php',
    __DIR__ . '/bootstrap/cache/services.php',
    __DIR__ . '/bootstrap/cache/config.php',
];

foreach ($paths as $file) {
    if (is_file($file)) {
        opcache_compile_file($file);
    }
}

function preloadDir($dir) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if ($file->isFile() && substr($file, -4) === '.php') {
            @opcache_compile_file($file->getPathname());
        }
    }
}

preloadDir(__DIR__ . '/vendor/laravel/framework/src/Illuminate');
