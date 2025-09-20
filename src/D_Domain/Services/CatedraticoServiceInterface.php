<?php
declare(strict_types=1);

namespace App\D_Domain\Services;

use App\D_Domain\DTOs\CatedraticoRequestDTO;
use App\D_Domain\DTOs\CatedraticoResponseDTO;

interface CatedraticoServiceInterface
{
    public function create(CatedraticoRequestDTO $in): CatedraticoResponseDTO;
    public function list(): array;
    public function getById(int $id): ?CatedraticoResponseDTO;
    public function update(int $id, CatedraticoRequestDTO $in): ?CatedraticoResponseDTO;
    public function delete(int $id): bool;
}
