<?php
namespace S_Shared\Mapping;

/**
 * Contrato genérico para todos los mappers.
 * - fromDTO:      DTO -> Entidad de dominio
 * - toDTO:        Entidad -> DTO
 * - toPersistence:Entidad -> array (columnas BD)
 * - fromPersistence: array (fila BD) -> Entidad
 */
interface MapperInterface
{
    /** @param mixed $dto */
    public function fromDTO($dto);

    /** @return mixed */
    public function toDTO($entity);

    public function toPersistence($entity): array;

    public function fromPersistence(array $row);
}
