<?php
// Configurações da aplicação
define('BASE_URL', '/seekers_mvc');
define('APP_NAME', 'SEEKERS');

// Autoload das classes
spl_autoload_register(function ($class) {
    $paths = [
        'app/models/',
        'app/controllers/',
        'config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Iniciar sessão
session_start();