<?php
declare(strict_types=1);
namespace App\D_Domain\Services;
use App\D_Domain\DTOs\AlumnoDTO;
/** Interfaz servicio Alumno [ISP][DIP] */
interface AlumnoServiceInterface {
  public function listar(): array;
  public function obtener(int $id): array;
  public function crear(AlumnoDTO $dto): int;
  public function actualizar(int $id, AlumnoDTO $dto): void;
  public function eliminar(int $id): void;
}
