<?php
declare(strict_types=1);

use App\P_Presentation\Http\Controllers\AlumnoController;

/**
 * Aquí ya existen $router y $container (creados en public/index.php)
 */

// Healthcheck
$router->get('/_health', function () {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => true, 'status' => 'running']);
});

// ---- Alumnos ----

// Listar todos
$router->get('/alumnos', function() use ($container) {
    (new AlumnoController($container->alumnoService()))->index();
});

// Crear
$router->post('/alumnos', function() use ($container) {
    (new AlumnoController($container->alumnoService()))->create();
});

// Obtener por ID  (GET /alumnos/{id})
$router->get('/alumnos/{id}', function() use ($container) {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
    $id   = (int) basename($path); // toma el último segmento como id
    (new AlumnoController($container->alumnoService()))->show($id);
});

// Actualizar por ID  (PUT /alumnos/{id})
$router->put('/alumnos/{id}', function() use ($container) {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
    $id   = (int) basename($path);
    (new AlumnoController($container->alumnoService()))->update($id);
});

// Eliminar por ID  (DELETE /alumnos/{id})
$router->delete('/alumnos/{id}', function() use ($container) {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
    $id   = (int) basename($path);
    (new AlumnoController($container->alumnoService()))->destroy($id);
});
