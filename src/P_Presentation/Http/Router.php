<?php
declare(strict_types=1);

namespace App\P_Presentation\Http;

/**
 * router minimalista por patrón (método + regex)
 * - yo solo decido a qué handler llamar y le paso los params capturados
 * - no hago lógica de negocio acá
 */
final class Router
{
    private array $routes = [];

    // agrega una ruta con método y patrón (regex)
    public function add(string $method, string $pattern, callable $handler): void
    {
        $this->routes[] = [$method, "#^{$pattern}$#", $handler];
    }

    // despacha según request method + uri
    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes as [$m, $regex, $handler]) {
            if ($m === $method && preg_match($regex, $path, $mats)) {
                array_shift($mats);        // saco el match completo
                $handler(...$mats);        // paso solo los params capturados
                return;
            }
        }
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'data' => ['error' => 'ruta no encontrada']], JSON_UNESCAPED_UNICODE);
    }
}
