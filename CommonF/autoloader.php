<?php

spl_autoload_register(function ($class) {
    // Folder mappings
    $map = [
        'CommonF\\' => __DIR__ . '/src/',
        'Apps\\' => __DIR__ . '/../Apps/',
    ];

    foreach ($map as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            
            if (file_exists($file) && is_readable($file)) {
                require $file;
                return;
            }
        }
    }
});
