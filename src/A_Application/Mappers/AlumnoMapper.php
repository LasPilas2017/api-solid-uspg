<?php
namespace A_Application\Mappers;

use S_Shared\Mapping\MapperInterface;
use D_Domain\DTOs\AlumnoDTO;
use D_Domain\Entities\Alumno;

/**
 * Traduce entre:
 *  - DTO <-> Entidad
 *  - Entidad <-> array para BD (snake_case)
 * Ajustá los nombres de propiedades si tu DTO/Entidad difiere.
 */
class AlumnoMapper implements MapperInterface
{
    public function fromDTO($dto): Alumno
    {
        /** @var AlumnoDTO $dto */
        return new Alumno(
            $dto->id ?? null,
            $dto->nombreCompleto,
            $dto->carnet,
            $dto->carrera,
            new \DateTimeImmutable($dto->fechaIngreso ?? date('Y-m-d'))
        );
    }

    public function toDTO($entity): AlumnoDTO
    {
        /** @var Alumno $entity */
        return new AlumnoDTO([
            'id'             => $entity->getId(),
            'nombreCompleto' => $entity->getNombreCompleto(),
            'carnet'         => $entity->getCarnet(),
            'carrera'        => $entity->getCarrera(),
            'fechaIngreso'   => $entity->getFechaIngreso()->format('Y-m-d'),
        ]);
    }

    public function toPersistence($entity): array
    {
        /** @var Alumno $entity */
        return [
            'id'              => $entity->getId(),
            'nombre_completo' => $entity->getNombreCompleto(),
            'carnet'          => $entity->getCarnet(),
            'carrera'         => $entity->getCarrera(),
            'fecha_ingreso'   => $entity->getFechaIngreso()->format('Y-m-d'),
        ];
    }

    public function fromPersistence(array $row): Alumno
    {
        return new Alumno(
            isset($row['id']) ? (int)$row['id'] : null,
            $row['nombre_completo'] ?? '',
            $row['carnet'] ?? '',
            $row['carrera'] ?? '',
            new \DateTimeImmutable($row['fecha_ingreso'] ?? date('Y-m-d'))
        );
    }
}
