<?php
declare(strict_types=1);
namespace App\D_Domain\Repositories;
use App\D_Domain\Entities\Alumno;
/** Interfaz repo Alumno [ISP][DIP] */
interface AlumnoRepositoryInterface {
  /** @return Alumno[] */ public function all(): array;
  public function find(int $id): ?Alumno;
  public function create(Alumno $alumno): int;
  public function update(Alumno $alumno): void;
  public function delete(int $id): void;
}
