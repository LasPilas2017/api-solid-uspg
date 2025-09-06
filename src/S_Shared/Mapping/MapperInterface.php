<?php
declare(strict_types=1);

namespace App\S_Shared\Mapping;

interface MapperInterface
{
    /** @param mixed $dto */
    public function fromDTO($dto);

    /** @return mixed */
    public function toDTO($entity);

    /** Entidad -> array para BD */
    public function toPersistence($entity): array;

    /** Fila BD -> Entidad */
    public function fromPersistence(array $row);
}
