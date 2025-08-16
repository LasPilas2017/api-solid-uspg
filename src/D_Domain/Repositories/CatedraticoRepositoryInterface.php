<?php
declare(strict_types=1);
namespace App\D_Domain\Repositories;
use App\D_Domain\Entities\Catedratico;
/** Interfaz repo Catedratico [ISP][DIP] */
interface CatedraticoRepositoryInterface {
  /** @return Catedratico[] */ public function all(): array;
  public function find(int $id): ?Catedratico;
  public function create(Catedratico $c): int;
  public function update(Catedratico $c): void;
  public function delete(int $id): void;
}
