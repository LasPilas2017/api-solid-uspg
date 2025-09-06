<?php
declare(strict_types=1);

use App\P_Presentation\Http\Controllers\AlumnoController;
use App\A_Application\Services\AlumnoService;

/**
 * Definición de rutas del sistema
 * Aquí ya existen $router y $container (se crean en public/index.php)
 */

// Healthcheck rápido
$router->get('/_health', function () {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => true, 'status' => 'running']);
});

// Alumnos
$router->get('/alumnos', function() use ($container) {
    $controller = new AlumnoController($container->alumnoService());
    $controller->index();
});

$router->post('/alumnos', function() use ($container) {
    $controller = new AlumnoController($container->alumnoService());
    $controller->create();
});
