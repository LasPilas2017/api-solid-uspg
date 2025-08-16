<?php
declare(strict_types=1);

namespace App\D_Domain\Entities;

/**
 * Entidad Catedratico (según tu tabla):
 * id, nombre, especialidad, correo
 * [SRP]
 */
final class Catedratico {
  public function __construct(
    private ?int $id,
    private string $nombre,
    private string $especialidad,
    private string $correo
  ) {}

  public function id(): ?int { return $this->id; }
  public function nombre(): string { return $this->nombre; }
  public function especialidad(): string { return $this->especialidad; }
  public function correo(): string { return $this->correo; }

  public function renombrar(string $nuevo): void { $this->nombre = $nuevo; }
  public function cambiarEspecialidad(string $nueva): void { $this->especialidad = $nueva; }
  public function cambiarCorreo(string $nuevo): void { $this->correo = $nuevo; }
}
