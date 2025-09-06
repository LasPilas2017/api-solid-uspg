<?php
declare(strict_types=1);

namespace App\P_Presentation\Http\Controllers;

use App\A_Application\Services\AlumnoService;
use App\D_Domain\DTOs\AlumnoDTO;

final class AlumnoController
{
    public function __construct(private AlumnoService $service) {}

    // GET /alumnos
    public function index(): void
    {
        $data = $this->service->list();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    }

    // POST /alumnos
    public function create(): void
{
    $raw  = file_get_contents('php://input') ?: '{}';
    $body = json_decode($raw, true) ?? [];

    // Construir DTO con ARGUMENTOS POSICIONALES (no array)
    $dtoIn = new \App\D_Domain\DTOs\AlumnoDTO(
        null, // id
        (string)($body['nombre'] ?? ''),
        (string)($body['carnet'] ?? ''),
        (string)($body['carrera'] ?? ''),
        (string)($body['fecha_ingreso'] ?? date('Y-m-d'))
    );

    $dtoOut = $this->service->create($dtoIn)->toArray();

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(201);
    echo json_encode(['ok' => true, 'data' => $dtoOut], JSON_UNESCAPED_UNICODE);
}
public function show(int $id): void
{
    $data = $this->service->obtener($id); // array (vacío si no existe)
    $status = empty($data) ? 404 : 200;
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($status);
    echo json_encode(['ok' => !empty($data), 'data' => $data], JSON_UNESCAPED_UNICODE);
}

public function update(int $id): void
{
    $raw  = file_get_contents('php://input') ?: '{}';
    $body = json_decode($raw, true) ?? [];

    // DTO posicional (tu DTO no acepta array en el constructor)
    $dto = new \App\D_Domain\DTOs\AlumnoDTO(
        $id,
        (string)($body['nombre'] ?? ''),
        (string)($body['carnet'] ?? ''),
        (string)($body['carrera'] ?? ''),
        (string)($body['fecha_ingreso'] ?? date('Y-m-d'))
    );

    // Ejecuta actualización (void)
    $this->service->actualizar($id, $dto);

    // Trae lo que quedó en BD y lo devuelve (si no existe, array vacío)
    $data = $this->service->obtener($id);

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(empty($data) ? 404 : 200);
    echo json_encode(['ok' => !empty($data), 'data' => $data], JSON_UNESCAPED_UNICODE);
}


public function destroy(int $id): void
{
    $this->service->eliminar($id); // void
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => true]);
}

}
