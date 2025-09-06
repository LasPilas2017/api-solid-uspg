<?php
declare(strict_types=1);

namespace App\P_Presentation\Http;

final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [
        'GET'  => [],
        'POST' => [],
        'PUT'  => [],
        'PATCH'=> [],
        'DELETE'=> [],
    ];

    public function get(string $path, callable $handler): void    { $this->routes['GET'][$this->norm($path)] = $handler; }
    public function post(string $path, callable $handler): void   { $this->routes['POST'][$this->norm($path)] = $handler; }
    public function put(string $path, callable $handler): void    { $this->routes['PUT'][$this->norm($path)] = $handler; }
    public function patch(string $path, callable $handler): void  { $this->routes['PATCH'][$this->norm($path)] = $handler; }
    public function delete(string $path, callable $handler): void { $this->routes['DELETE'][$this->norm($path)] = $handler; }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->norm(parse_url($uri, PHP_URL_PATH) ?? '/');
        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            http_response_code(404);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok'=>false,'error'=>'Not Found','path'=>$path,'method'=>$method]);
            return;
        }
        $handler(); // ejecuta el closure registrado en routes.php
    }

    private function norm(string $path): string
    {
        // normaliza: sin trailing slash (excepto raíz), y en minúsculas si querés
        if ($path !== '/' && str_ends_with($path, '/')) $path = rtrim($path, '/');
        return $path;
    }
}
