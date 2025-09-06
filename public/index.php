<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use App\B_Bootstrap\Container;
use App\P_Presentation\Http\Router;

// 1) Crear container y router
$container = new Container();
$router    = new Router();

// 2) Registrar rutas
require __DIR__ . '/../src/P_Presentation/Http/routes.php';

// 3) Despachar quitando el prefijo base (/api-solid-uspg/public)
$method = $_SERVER['REQUEST_METHOD'];
$full   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// base = carpeta donde vive index.php (p. ej. /api-solid-uspg/public)
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');          // => /api-solid-uspg/public
$path = $full;

// si el path empieza con el base, se lo quitamos
if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}
if ($path === '') { $path = '/'; }

// 4) Despacho
$router->dispatch($method, $path);
