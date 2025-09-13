<?php
declare(strict_types=1);

namespace App\D_Domain\Services;

use App\D_Domain\DTOs\AlumnoRequestDTO;
use App\D_Domain\DTOs\AlumnoResponseDTO;

interface AlumnoServiceInterface
{
    public function create(AlumnoRequestDTO $in): AlumnoResponseDTO;
    public function list(): array;
    public function getById(int $id): ?AlumnoResponseDTO;
    public function update(int $id, AlumnoRequestDTO $in): ?AlumnoResponseDTO;
    public function delete(int $id): bool;
}
