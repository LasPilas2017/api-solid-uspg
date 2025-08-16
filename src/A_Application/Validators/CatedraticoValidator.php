<?php
declare(strict_types=1);

namespace App\A_Application\Validators;

use App\S_Shared\Errors\ValidationException;

/**
 * Validador Catedratico [SRP]
 */
final class CatedraticoValidator {
  public function validateCreate(array $data): void {
    if (empty($data['nombre'])) { throw new ValidationException('El nombre es requerido'); }
    if (empty($data['especialidad'])) { throw new ValidationException('La especialidad es requerida'); }
    if (empty($data['correo']) || !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
      throw new ValidationException('El correo es inválido');
    }
  }
  public function validateUpdate(array $data): void {
    if (isset($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
      throw new ValidationException('El correo es inválido');
    }
  }
}
