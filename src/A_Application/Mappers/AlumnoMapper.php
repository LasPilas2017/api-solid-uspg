<?php
declare(strict_types=1);

namespace App\A_Application\Mappers;

use App\S_Shared\Mapping\MapperInterface;
use App\D_Domain\DTOs\AlumnoDTO;
use App\D_Domain\Entities\Alumno;

/**
 * Mapper de Alumno:
 *  - DTO <-> Entidad de dominio
 *  - Entidad <-> array de persistencia (snake_case)
 *
 * NOTA: La Entidad Alumno recibe fechaIngreso como STRING (Y-m-d),
 * así que aquí SIEMPRE convertimos a string al construir la Entidad.
 */
final class AlumnoMapper implements MapperInterface
{
    /** @param AlumnoDTO $dto */
    public function fromDTO($dto): Alumno
    {
        // usar fecha_ingreso (snake) o fechaIngreso (camel), si no viene usamos hoy
        $fecha = $dto->fecha_ingreso ?? ($dto->fechaIngreso ?? date('Y-m-d'));

        return new Alumno(
            $dto->id ?? null,
            (string)($dto->nombre ?? ''),
            (string)($dto->carnet ?? ''),
            (string)($dto->carrera ?? ''),
            (string)$fecha // <- STRING
        );
    }

    /** @return AlumnoDTO */
    public function toDTO($entity): AlumnoDTO
{
    /** @var \App\D_Domain\Entities\Alumno $entity */
    return new AlumnoDTO(
        $entity->id(),            // ?int
        $entity->nombre(),        // string
        $entity->carnet(),        // string
        $entity->carrera(),       // string
        (string)$entity->fechaIngreso() // string 'Y-m-d'
    );
}


    /** Entidad -> array (para repo/persistencia) */
    public function toPersistence($entity): array
    {
        /** @var Alumno $entity */
        return [
            'id'            => $entity->id(),
            'nombre'        => $entity->nombre(),
            'carnet'        => $entity->carnet(),
            'carrera'       => $entity->carrera(),
            'fecha_ingreso' => (string)$entity->fechaIngreso(), // <- STRING
        ];
    }

    /** Fila BD -> Entidad */
    public function fromPersistence(array $row): Alumno
    {
        return new Alumno(
            isset($row['id']) ? (int)$row['id'] : null,
            (string)($row['nombre'] ?? ''),
            (string)($row['carnet'] ?? ''),
            (string)($row['carrera'] ?? ''),
            (string)($row['fecha_ingreso'] ?? date('Y-m-d')) // <- STRING
        );
    }
}
