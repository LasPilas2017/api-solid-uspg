<?php
declare(strict_types=1);

namespace App\D_Domain\DTOs;

/**
 * DTO Catedratico: id, nombre, especialidad, correo [SRP]
 */
final class CatedraticoDTO {
  public function __construct(
    public ?int $id,
    public string $nombre,
    public string $especialidad,
    public string $correo
  ) {}

  public static function fromArray(array $in): self {
    return new self(
      $in['id'] ?? null,
      isset($in['nombre']) ? trim((string)$in['nombre']) : '',
      isset($in['especialidad']) ? trim((string)$in['especialidad']) : '',
      isset($in['correo']) ? trim((string)$in['correo']) : ''
    );
  }

  public function toArray(): array {
    return [
      'id' => $this->id,
      'nombre' => $this->nombre,
      'especialidad' => $this->especialidad,
      'correo' => $this->correo,
    ];
  }
}
