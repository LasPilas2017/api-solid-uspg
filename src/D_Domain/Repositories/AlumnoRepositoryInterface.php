<?php
declare(strict_types=1);

namespace App\D_Domain\Repositories;

interface AlumnoRepositoryInterface
{
    public function insert(array $row): int;
    public function findAll(): array;
    public function findById(int $id): ?array;
    public function update(int $id, array $row): bool;
    public function delete(int $id): bool; // ← bool
}
