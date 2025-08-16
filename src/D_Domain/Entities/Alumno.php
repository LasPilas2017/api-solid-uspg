<?php
declare(strict_types=1);

namespace App\D_Domain\Entities;

/**
 * Entidad Alumno (alineada a tu tabla):
 * id, nombre, carnet, carrera, fecha_ingreso
 * [SRP]
 */
final class Alumno {
  public function __construct(
    private ?int $id,
    private string $nombre,
    private string $carnet,
    private string $carrera,
    private string $fechaIngreso // formato 'Y-m-d'
  ) {}

  public function id(): ?int { return $this->id; }
  public function nombre(): string { return $this->nombre; }
  public function carnet(): string { return $this->carnet; }
  public function carrera(): string { return $this->carrera; }
  public function fechaIngreso(): string { return $this->fechaIngreso; }

  public function renombrar(string $nuevo): void { $this->nombre = $nuevo; }
  public function cambiarCarnet(string $nuevo): void { $this->carnet = $nuevo; }
  public function cambiarCarrera(string $nuevo): void { $this->carrera = $nuevo; }
  public function cambiarFechaIngreso(string $nueva): void { $this->fechaIngreso = $nueva; }
}
