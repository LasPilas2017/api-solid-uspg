<?php
declare(strict_types=1);

namespace App\A_Application\Services;

use App\D_Domain\Services\CatedraticoServiceInterface;
use App\D_Domain\Repositories\CatedraticoRepositoryInterface;
use App\D_Domain\DTOs\CatedraticoDTO;
use App\D_Domain\Entities\Catedratico;
use App\A_Application\Validators\CatedraticoValidator;
use App\S_Shared\Errors\AppException;

/**
 * Servicio Catedratico [SRP]
 */
final class CatedraticoService implements CatedraticoServiceInterface {
  public function __construct(
    private CatedraticoRepositoryInterface $repo,
    private CatedraticoValidator $validator
  ) {}

  public function listar(): array {
    return array_map(
      fn(Catedratico $c) => [
        'id' => $c->id(),
        'nombre' => $c->nombre(),
        'especialidad' => $c->especialidad(),
        'correo' => $c->correo(),
      ],
      $this->repo->all()
    );
  }

  public function obtener(int $id): array {
    $c = $this->repo->find($id);
    if (!$c) { throw new AppException('Catedratico no encontrado', 404); }
    return [
      'id' => $c->id(),
      'nombre' => $c->nombre(),
      'especialidad' => $c->especialidad(),
      'correo' => $c->correo(),
    ];
  }

  public function crear(CatedraticoDTO $dto): int {
    $this->validator->validateCreate($dto->toArray());
    $c = new Catedratico(null, $dto->nombre, $dto->especialidad, $dto->correo);
    return $this->repo->create($c);
  }

  public function actualizar(int $id, CatedraticoDTO $dto): void {
    $this->validator->validateUpdate($dto->toArray());
    $exist = $this->repo->find($id);
    if (!$exist) { throw new AppException('Catedratico no encontrado', 404); }
    if ($dto->nombre) $exist->renombrar($dto->nombre);
    if ($dto->especialidad) $exist->cambiarEspecialidad($dto->especialidad);
    if ($dto->correo) $exist->cambiarCorreo($dto->correo);
    $this->repo->update($exist);
  }

  public function eliminar(int $id): void { $this->repo->delete($id); }
}
