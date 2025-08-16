<?php
declare(strict_types=1);

namespace App\A_Application\Validators;

use App\S_Shared\Errors\ValidationException;

/**
 * Validador Alumno [SRP]
 */
final class AlumnoValidator {
  private function validateDateYmd(string $date): bool {
    $d = \DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
  }

  public function validateCreate(array $data): void {
    if (empty($data['nombre'])) { throw new ValidationException('El nombre es requerido'); }
    if (empty($data['carnet'])) { throw new ValidationException('El carnet es requerido'); }
    if (empty($data['carrera'])) { throw new ValidationException('La carrera es requerida'); }
    if (empty($data['fecha_ingreso']) || !$this->validateDateYmd($data['fecha_ingreso'])) {
      throw new ValidationException('fecha_ingreso inválida (use Y-m-d)');
    }
  }

  public function validateUpdate(array $data): void {
    if (isset($data['fecha_ingreso']) && !$this->validateDateYmd((string)$data['fecha_ingreso'])) {
      throw new ValidationException('fecha_ingreso inválida (use Y-m-d)');
    }
  }
}
