<?php
declare(strict_types=1);

namespace App\A_Application\Services;

use App\D_Domain\Services\AlumnoServiceInterface;
use App\D_Domain\Repositories\AlumnoRepositoryInterface;
use App\D_Domain\DTOs\AlumnoDTO;
use App\D_Domain\Entities\Alumno;
use App\A_Application\Validators\AlumnoValidator;
use App\S_Shared\Errors\AppException;

/**
 * Servicio Alumno [SRP][DIP]
 */
final class AlumnoService implements AlumnoServiceInterface {
  public function __construct(
    private AlumnoRepositoryInterface $repo,
    private AlumnoValidator $validator
  ) {}

  public function listar(): array {
    return array_map(
      fn(Alumno $a) => [
        'id' => $a->id(),
        'nombre' => $a->nombre(),
        'carnet' => $a->carnet(),
        'carrera' => $a->carrera(),
        'fecha_ingreso' => $a->fechaIngreso(),
      ],
      $this->repo->all()
    );
  }

  public function obtener(int $id): array {
    $a = $this->repo->find($id);
    if (!$a) { throw new AppException('Alumno no encontrado', 404); }
    return [
      'id' => $a->id(),
      'nombre' => $a->nombre(),
      'carnet' => $a->carnet(),
      'carrera' => $a->carrera(),
      'fecha_ingreso' => $a->fechaIngreso(),
    ];
  }

  public function crear(AlumnoDTO $dto): int {
    $this->validator->validateCreate($dto->toArray());
    $alumno = new Alumno(null, $dto->nombre, $dto->carnet, $dto->carrera, $dto->fecha_ingreso);
    return $this->repo->create($alumno);
  }

  public function actualizar(int $id, AlumnoDTO $dto): void {
    $this->validator->validateUpdate($dto->toArray());
    $exist = $this->repo->find($id);
    if (!$exist) { throw new AppException('Alumno no encontrado', 404); }
    if ($dto->nombre) $exist->renombrar($dto->nombre);
    if ($dto->carnet) $exist->cambiarCarnet($dto->carnet);
    if ($dto->carrera) $exist->cambiarCarrera($dto->carrera);
    if ($dto->fecha_ingreso) $exist->cambiarFechaIngreso($dto->fecha_ingreso);
    $this->repo->update($exist);
  }

  public function eliminar(int $id): void { $this->repo->delete($id); }
}
