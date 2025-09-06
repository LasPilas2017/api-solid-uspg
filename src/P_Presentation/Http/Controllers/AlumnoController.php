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

        // Construir DTO desde el body
        $dtoIn = new AlumnoDTO($body);

        // Crear y devolver DTO como array
        $dtoOut = $this->service->create($dtoIn)->toArray();

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(201);
        echo json_encode(['ok' => true, 'data' => $dtoOut], JSON_UNESCAPED_UNICODE);
    }
}
