<?php
declare(strict_types=1);

namespace App\A_Application\Mappers;

use App\D_Domain\DTOs\CatedraticoRequestDTO;
use App\D_Domain\DTOs\CatedraticoResponseDTO;
use App\D_Domain\Entities\Catedratico;

final class CatedraticoMapper
{
    /**
     * fromRequestDTO: convierto lo que entra del cliente (requestdto) a mi entidad de dominio
     * - si me pasan $base es porque estoy en update y quiero conservar lo ya existente (created_at/by, etc.)
     * - si no hay $base, asumo que es create y yo seteo created_at y created_by
     */
    public function fromRequestDTO(CatedraticoRequestDTO $dto, ?Catedratico $base = null): Catedratico
    {
        // si no hay entidad base, estoy creando. dejo created_at ahora y created_by con el actor (o system)
        $createdAt = $base?->createdAt() ?? date('Y-m-d H:i:s');
        $createdBy = $base?->createdBy() ?? ($dto->actor ?? 'system');

        // en update quiero respetar lo que ya estaba creado y solo marcar quién actualiza
        $updatedAt = $base?->updatedAt(); // mysql lo puede setear con on update, igual acá lo dejo pasar
        $updatedBy = $dto->actor ?? $base?->updatedBy();

        return new Catedratico(
            id: $base?->id() ?? null,
            nombre: trim($dto->nombre),
            especialidad: trim($dto->especialidad),
            correo: strtolower(trim($dto->correo)),
            created_at: $createdAt,
            updated_at: $updatedAt,
            created_by: $createdBy,
            updated_by: $updatedBy,
            deleted_at: $base?->deletedAt()
        );
    }

    /**
     * toResponseDTO: lo que siempre devuelvo al cliente (incluye auditoría)
     * - acá no hago lógica, solo empaqueto los datos de la entidad para el response
     */
    public function toResponseDTO(Catedratico $e): CatedraticoResponseDTO
    {
        $out = new CatedraticoResponseDTO();
        $out->id = (int)$e->id();
        $out->nombre = $e->nombre();
        $out->especialidad = $e->especialidad();
        $out->correo = $e->correo();
        $out->created_at = $e->createdAt();
        $out->updated_at = $e->updatedAt();
        $out->created_by = $e->createdBy();
        $out->updated_by = $e->updatedBy();
        $out->deleted_at = $e->deletedAt();
        return $out;
    }

    /**
     * toPersistence: dejo la entidad lista para que el repositorio la guarde con mysqli
     * - las claves deben coincidir con los nombres de columnas en la tabla
     */
    public function toPersistence(Catedratico $e): array
    {
        return [
            'nombre' => $e->nombre(),
            'especialidad' => $e->especialidad(),
            'correo' => $e->correo(),
            'created_at' => $e->createdAt(),
            'updated_at' => $e->updatedAt(),
            'created_by' => $e->createdBy(),
            'updated_by' => $e->updatedBy(),
            'deleted_at' => $e->deletedAt(),
        ];
    }

    /**
     * fromPersistence: convierto una fila cruda de mysql (fetch_assoc) a la entidad de dominio
     * - acá asumo que el repo ya seleccionó todas las columnas necesarias
     */
    public function fromPersistence(array $row): Catedratico
    {
        return new Catedratico(
            id: (int)$row['id'],
            nombre: (string)$row['nombre'],
            especialidad: (string)$row['especialidad'],
            correo: (string)$row['correo'],
            created_at: (string)$row['created_at'],
            updated_at: $row['updated_at'] ?? null,
            created_by: $row['created_by'] ?? null,
            updated_by: $row['updated_by'] ?? null,
            deleted_at: $row['deleted_at'] ?? null
        );
    }
}
