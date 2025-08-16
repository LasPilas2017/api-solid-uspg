<?php
declare(strict_types=1);

namespace App\D_Domain\DTOs;

/**
 * DTO Alumno con: id, nombre, carnet, carrera, fecha_ingreso [SRP]
 */
final class AlumnoDTO {
  public function __construct(
    public ?int $id,
    public string $nombre,
    public string $carnet,
    public string $carrera,
    public string $fecha_ingreso
  ) {}

  public static function fromArray(array $in): self {
    return new self(
      $in['id'] ?? null,
      isset($in['nombre']) ? trim((string)$in['nombre']) : '',
      isset($in['carnet']) ? trim((string)$in['carnet']) : '',
      isset($in['carrera']) ? trim((string)$in['carrera']) : '',
      isset($in['fecha_ingreso']) ? trim((string)$in['fecha_ingreso']) : ''
    );
  }

  public function toArray(): array {
    return [
      'id' => $this->id,
      'nombre' => $this->nombre,
      'carnet' => $this->carnet,
      'carrera' => $this->carrera,
      'fecha_ingreso' => $this->fecha_ingreso,
    ];
  }
}
