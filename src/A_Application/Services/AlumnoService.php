<?php
declare(strict_types=1);

namespace App\A_Application\Services;

use App\D_Domain\DTOs\AlumnoRequestDTO;
use App\D_Domain\DTOs\AlumnoResponseDTO;
use App\A_Application\Mappers\AlumnoMapper;
use App\D_Domain\Repositories\AlumnoRepositoryInterface;
use App\D_Domain\Services\AlumnoServiceInterface;

/**
 * servicio de alumnos
 * - acá orquesto el caso de uso usando repo + mapper
 * - no hago sql directo ni json acá; solo lógica de aplicación
 */
final class AlumnoService implements AlumnoServiceInterface
{
    public function __construct(
        private AlumnoRepositoryInterface $repo,
        private AlumnoMapper $mapper
    ) {}

    /**
     * create:
     * - mapeo del requestdto a entidad (el mapper setea created_at/by)
     * - convierto a array para repo->insert
     * - leo de vuelta desde la bd para devolver el responsedto con auditoría real
     */
    public function create(AlumnoRequestDTO $in): AlumnoResponseDTO
    {
        $entidad = $this->mapper->fromRequestDTO($in);
        $row     = $this->mapper->toPersistence($entidad);
        $id      = $this->repo->insert($row);

        $fresh   = $this->repo->findById($id);           // traigo lo que quedó realmente en bd
        $e       = $this->mapper->fromPersistence($fresh);
        return $this->mapper->toResponseDTO($e);
    }

    /**
     * list:
     * - traigo filas crudas
     * - por cada fila hago array -> entidad -> responsedto
     */
    public function list(): array
    {
        $rows = $this->repo->findAll();
        $out = [];
        foreach ($rows as $r) {
            $e = $this->mapper->fromPersistence($r);
            $out[] = $this->mapper->toResponseDTO($e);
        }
        return $out;
    }

    /**
     * getById:
     * - si no existe, regreso null
     * - si existe, devuelvo responsedto
     */
    public function getById(int $id): ?AlumnoResponseDTO
    {
        $row = $this->repo->findById($id);
        if (!$row) return null;

        $e = $this->mapper->fromPersistence($row);
        return $this->mapper->toResponseDTO($e);
    }

    /**
     * update:
     * - cargo el actual para conservar created_at/by
     * - mapeo requestdto + base -> entidad
     * - paso a array y hago update
     * - leo de nuevo para devolver responsedto consistente (updated_at real)
     */
    public function update(int $id, AlumnoRequestDTO $in): ?AlumnoResponseDTO
    {
        $current = $this->repo->findById($id);
        if (!$current) return null;

        $base = $this->mapper->fromPersistence($current);
        $entidad = $this->mapper->fromRequestDTO($in, $base);
        $row = $this->mapper->toPersistence($entidad);

        $ok = $this->repo->update($id, $row);
        if (!$ok) return null;

        $fresh = $this->repo->findById($id);
        $e = $this->mapper->fromPersistence($fresh);
        return $this->mapper->toResponseDTO($e);
    }

    /**
     * delete:
     * - por ahora es borrado duro
     * - si después usamos soft delete, acá solo llamo a repo->softDelete
     */
    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }
}
