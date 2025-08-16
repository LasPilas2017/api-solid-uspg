<?php
declare(strict_types=1);
namespace App\D_Domain\Services;
use App\D_Domain\DTOs\CatedraticoDTO;
/** Interfaz servicio Catedratico [ISP][DIP] */
interface CatedraticoServiceInterface {
  public function listar(): array;
  public function obtener(int $id): array;
  public function crear(CatedraticoDTO $dto): int;
  public function actualizar(int $id, CatedraticoDTO $dto): void;
  public function eliminar(int $id): void;
}
