<?php
declare(strict_types=1);

namespace App\D_Domain\Services;

use App\D_Domain\DTOs\AlumnoDTO;

/**
 * Contrato del servicio de aplicación para Alumno.
 * Trabaja con DTOs (no con entidades) y retorna tipos simples para la capa presentación.
 */
interface AlumnoServiceInterface
{
    /**
     * Crear un alumno.
     * Debe retornar el ID generado.
     */
    public function crear(AlumnoDTO $dto): int;

    /**
     * Listar todos los alumnos.
     * Retorna un array de arrays (DTOs serializados) listo para JSON.
     * @return array<int, array<string, mixed>>
     */
    public function listar(): array;

    /**
     * Obtener un alumno por ID.
     * Si no existe, retornar [].
     * @return array<string, mixed>
     */
    public function obtener(int $id): array;

    /**
     * Actualizar un alumno por ID (sin retorno).
     */
    public function actualizar(int $id, AlumnoDTO $dto): void;

    /**
     * Eliminar un alumno por ID (sin retorno).
     */
    public function eliminar(int $id): void;
}
