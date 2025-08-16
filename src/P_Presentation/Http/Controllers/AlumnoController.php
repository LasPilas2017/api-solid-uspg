<?php
declare(strict_types=1);

namespace App\P_Presentation\Http\Controllers;

use App\D_Domain\Services\AlumnoServiceInterface;
use App\D_Domain\DTOs\AlumnoDTO;
use App\S_Shared\Http\JsonResponse;

/**
 * Controller Alumno [SRP][DIP]
 */
final class AlumnoController {
  public function __construct(private AlumnoServiceInterface $service) {}

  public function index(): void { JsonResponse::ok($this->service->listar()); }
  public function show(int $id): void { JsonResponse::ok($this->service->obtener($id)); }

  public function store(): void {
    $body = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $id = $this->service->crear(AlumnoDTO::fromArray($body));
    JsonResponse::created(['id' => $id]);
  }

  public function update(int $id): void {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $this->service->actualizar($id, AlumnoDTO::fromArray($body));
    JsonResponse::ok(['updated' => true]);
  }

  public function destroy(int $id): void {
    $this->service->eliminar($id);
    JsonResponse::ok(['deleted' => true]);
  }
}
