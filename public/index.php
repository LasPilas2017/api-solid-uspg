<?php
declare(strict_types=1);
/**
 * Front Controller [SRP]: punto de entrada único.
 * Carga autoload (Composer o manual) y delega al Router [DIP].
 */
$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($vendorAutoload)) {
  require $vendorAutoload;
} else {
  require __DIR__ . '/../src/autoload_manual.php';
}

use App\P_Presentation\Http\Router;
use App\B_Bootstrap\Container;
use App\S_Shared\Http\JsonResponse;
use App\S_Shared\Errors\AppException;

$container = new Container();
$router    = new Router($container);

try {
  $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (AppException $e) {
  JsonResponse::error($e->getMessage(), $e->getCode() ?: 400);
} catch (Throwable $e) {
    App\S_Shared\Http\JsonResponse::error(
    $e->getMessage().' @ '.$e->getFile().':'.$e->getLine(),
    500
  );
}
