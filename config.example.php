<?php
// Archivo de configuración de ejemplo
// Copiar a config.php y ajustar según tu entorno

return [
    'database' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'uspg',
        'charset' => 'utf8mb4'
    ],
    'app' => [
        'name' => 'API SOLID USPG',
        'version' => '1.0.0',
        'environment' => 'development',
        'debug' => true
    ],
    'cors' => [
        'allowed_origins' => ['*'],
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'X-User', 'Authorization']
    ]
];
