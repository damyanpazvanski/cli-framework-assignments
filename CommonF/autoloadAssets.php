<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If the file or directory exists physically on disk, let the server serve it directly
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    
    if ($extension === 'css') {
        header("Content-Type: text/css");
    } elseif ($extension === 'js') {
        header("Content-Type: text/javascript");
    }

    return false;
}

return true;

// --- YOUR REST OF INDEX.PHP APP LOGIC STARTS HERE ---
