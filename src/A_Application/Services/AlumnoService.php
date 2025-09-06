<?php
declare(strict_types=1);

namespace App\A_Application\Services;

use App\D_Domain\DTOs\AlumnoDTO;
use App\A_Application\Mappers\AlumnoMapper;
use App\D_Domain\Repositories\AlumnoRepositoryInterface;
use App\D_Domain\Services\AlumnoServiceInterface;

final class AlumnoService implements AlumnoServiceInterface
{
    public function __construct(
        private AlumnoRepositoryInterface $repo,
        private AlumnoMapper $mapper
    ) {}

    /** Crear alumno: DTO -> Entidad -> array BD -> repo -> DTO */
    public function create(AlumnoDTO $dto): AlumnoDTO
    {
        $entidad = $this->mapper->fromDTO($dto);
        $row     = $this->mapper->toPersistence($entidad);
        $id      = $this->repo->insert($row);

        $dto->id = $id;
        return $dto;
    }

    /** Listar: filas BD -> Entidad -> DTO[] (array listo para JSON) */
    public function list(): array
    {
        $rows = $this->repo->fetchAll();
        $out  = [];
        foreach ($rows as $r) {
            $ent   = $this->mapper->fromPersistence($r);
            $out[] = $this->mapper->toDTO($ent)->toArray();
        }
        return $out;
    }

    /** Obtener por id (no-nullable para cumplir el interface) */
    public function get(int $id): array
    {
        $row = $this->repo->findById($id);
        if (!$row) {
            return []; // si no existe, devolvemos arreglo vacío
        }
        $ent = $this->mapper->fromPersistence($row);
        return $this->mapper->toDTO($ent)->toArray();
    }

    /** Actualizar por id: DTO -> Entidad -> array -> repo */
    public function update(int $id, AlumnoDTO $dto): void
    {
        $ent = $this->mapper->fromDTO($dto);
        $row = $this->mapper->toPersistence($ent);
        $row['id'] = $id;
        $this->repo->updateById($id, $row); // interface pide void
    }

    /** Eliminar por id (void para cumplir el interface) */
    public function delete(int $id): void
    {
        $this->repo->delete($id);
    }

    /* ===== Wrappers con los nombres del INTERFACE (español) ===== */
    public function crear(AlumnoDTO $dto): int
    {
        $out = $this->create($dto);   // devuelve AlumnoDTO
        return (int) $out->id;        // el interface pide int (ID)
    }

    public function listar(): array                          { return $this->list(); }
    public function obtener(int $id): array                  { return $this->get($id); }
    public function actualizar(int $id, AlumnoDTO $dto): void { $this->update($id, $dto); }
    public function eliminar(int $id): void                  { $this->delete($id); }
}
