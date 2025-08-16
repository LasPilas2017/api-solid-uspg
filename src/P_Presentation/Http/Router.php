<?php
declare(strict_types=1);
namespace App\P_Presentation\Http;
use App\B_Bootstrap\Container;
use App\S_Shared\Http\JsonResponse;
/** Router mínimo [SRP][OCP] */
final class Router {
  public function __construct(private Container $c) {}
  public function dispatch(string $method, string $uri): void {
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';
    $base = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($base !== '' && str_starts_with($path, $base)) { $path = substr($path, strlen($base)) ?: '/'; }
    // alumnos
    if ($path === '/alumnos' && $method === 'GET')  { $this->c->alumnoController()->index(); return; }
    if ($path === '/alumnos' && $method === 'POST') { $this->c->alumnoController()->store(); return; }
    if (preg_match('#^/alumnos/(\d+)$#',$path,$m)) {
      $id=(int)$m[1];
      if ($method==='GET') { $this->c->alumnoController()->show($id); return; }
      if ($method==='PUT') { $this->c->alumnoController()->update($id); return; }
      if ($method==='DELETE'){ $this->c->alumnoController()->destroy($id); return; }
    }
    // catedraticos
    if ($path === '/catedraticos' && $method === 'GET')  { $this->c->catedraticoController()->index(); return; }
    if ($path === '/catedraticos' && $method === 'POST') { $this->c->catedraticoController()->store(); return; }
    if (preg_match('#^/catedraticos/(\d+)$#',$path,$m)) {
      $id=(int)$m[1];
      if ($method==='GET') { $this->c->catedraticoController()->show($id); return; }
      if ($method==='PUT') { $this->c->catedraticoController()->update($id); return; }
      if ($method==='DELETE'){ $this->c->catedraticoController()->destroy($id); return; }
    }
    JsonResponse::notFound();
  }
}
