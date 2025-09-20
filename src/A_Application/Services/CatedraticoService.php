<?php
declare(strict_types=1);

namespace App\A_Application\Services;

use App\D_Domain\DTOs\CatedraticoRequestDTO;
use App\D_Domain\DTOs\CatedraticoResponseDTO;
use App\A_Application\Mappers\CatedraticoMapper;
use App\A_Application\Validators\CatedraticoValidator;
use App\D_Domain\Repositories\CatedraticoRepositoryInterface;
use App\D_Domain\Services\CatedraticoServiceInterface;

/**
 * servicio de catedráticos
 * - acá orquesto el caso de uso usando repo + mapper
 * - no hago sql directo ni json acá; solo lógica de aplicación
 */
final class CatedraticoService implements CatedraticoServiceInterface
{
    public function __construct(
        private CatedraticoRepositoryInterface $repo,
        private CatedraticoMapper $mapper,
        private CatedraticoValidator $validator
    ) {}

    /**
     * create:
     * - mapeo del requestdto a entidad (el mapper setea created_at/by)
     * - convierto a array para repo->insert
     * - leo de vuelta desde la bd para devolver el responsedto con auditoría real
     */
    public function create(CatedraticoRequestDTO $in): CatedraticoResponseDTO
    {
        // validar datos de entrada
        $this->validator->validateCreate([
            'nombre' => $in->nombre,
            'especialidad' => $in->especialidad,
            'correo' => $in->correo
        ]);
        
        $entidad = $this->mapper->fromRequestDTO($in);
        $row = $this->mapper->toPersistence($entidad);
        $id = $this->repo->insert($row);

        $fresh = $this->repo->findById($id);           // traigo lo que quedó realmente en bd
        $e = $this->mapper->fromPersistence($fresh);
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
    public function getById(int $id): ?CatedraticoResponseDTO
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
    public function update(int $id, CatedraticoRequestDTO $in): ?CatedraticoResponseDTO
    {
        $current = $this->repo->findById($id);
        if (!$current) return null;

        // validar datos de entrada
        $this->validator->validateUpdate([
            'nombre' => $in->nombre,
            'especialidad' => $in->especialidad,
            'correo' => $in->correo
        ]);

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
     * - soft delete implementado
     */
    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }
}
