<?php
declare(strict_types=1);

use App\B_Bootstrap\Container;
use App\P_Presentation\Http\Router;

require_once __DIR__ . '/../vendor/autoload.php';

// instancio el container
$container = new Container();

// obtengo el controller de alumnos
$alumnos = $container->alumnoController();

// router minimal
$router = new Router();

// rutas de alumnos (rest)
$router->add('GET',    '/api-solid-uspg/public/alumnos',               fn()       => $alumnos->index());
$router->add('GET',    '/api-solid-uspg/public/alumnos/(\d+)',         fn($id)    => $alumnos->show((int)$id));
$router->add('POST',   '/api-solid-uspg/public/alumnos',               fn()       => $alumnos->store());
$router->add('PUT',    '/api-solid-uspg/public/alumnos/(\d+)',         fn($id)    => $alumnos->update((int)$id));
$router->add('DELETE', '/api-solid-uspg/public/alumnos/(\d+)',         fn($id)    => $alumnos->destroy((int)$id));

// despacha
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
