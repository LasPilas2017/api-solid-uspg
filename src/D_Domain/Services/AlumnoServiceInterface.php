<?php
declare(strict_types=1);

namespace App\D_Domain\Services;

use App\D_Domain\DTOs\AlumnoDTO;

interface AlumnoServiceInterface
{
    public function crear(AlumnoDTO $dto): int;
    public function listar(): array;
    public function obtener(int $id): array;
    public function actualizar(int $id, AlumnoDTO $dto): void;
    public function eliminar(int $id): void;
}
