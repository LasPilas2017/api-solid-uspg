<?php
declare(strict_types=1);

namespace App\I_Infrastructure\Config;

/**
 * Configuración de la aplicación
 */
final class AppConfig
{
    public static function getDatabaseConfig(): array
    {
        return [
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'database' => $_ENV['DB_DATABASE'] ?? 'uspg',
            'charset' => 'utf8mb4'
        ];
    }

    public static function getAppConfig(): array
    {
        return [
            'name' => 'API SOLID USPG',
            'version' => '1.0.0',
            'environment' => $_ENV['APP_ENV'] ?? 'development',
            'debug' => ($_ENV['APP_DEBUG'] ?? 'false') === 'true'
        ];
    }

    public static function getCorsConfig(): array
    {
        return [
            'allowed_origins' => explode(',', $_ENV['CORS_ORIGINS'] ?? '*'),
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            'allowed_headers' => ['Content-Type', 'X-User', 'Authorization']
        ];
    }
}
