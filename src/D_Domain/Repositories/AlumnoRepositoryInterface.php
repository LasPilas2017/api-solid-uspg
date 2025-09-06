<?php
declare(strict_types=1);

namespace App\D_Domain\Repositories;

/**
 * Contrato de persistencia para Alumno.
 * Usa ARRAYS en lugar de Entidades, porque el Mapper se encarga de traducir.
 */
interface AlumnoRepositoryInterface
{
    public function insert(array $data): int;

    public function fetchAll(): array;

    public function findById(int $id): ?array;

    public function updateById(int $id, array $data): void;

    public function delete(int $id): void;
}
