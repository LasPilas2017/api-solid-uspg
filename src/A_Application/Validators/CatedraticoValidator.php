<?php
declare(strict_types=1);

namespace App\A_Application\Validators;

use App\S_Shared\Errors\ValidationException;

/**
 * Validador Catedrático [SRP]
 */
final class CatedraticoValidator
{
    public function validateCreate(array $data): void
    {
        if (empty($data['nombre'])) {
            throw new ValidationException('El nombre es requerido');
        }
        
        if (empty($data['especialidad'])) {
            throw new ValidationException('La especialidad es requerida');
        }
        
        if (empty($data['correo'])) {
            throw new ValidationException('El correo es requerido');
        }
        
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('El correo es inválido');
        }
        
        if (strlen($data['nombre']) > 120) {
            throw new ValidationException('El nombre no puede exceder 120 caracteres');
        }
        
        if (strlen($data['especialidad']) > 120) {
            throw new ValidationException('La especialidad no puede exceder 120 caracteres');
        }
        
        if (strlen($data['correo']) > 120) {
            throw new ValidationException('El correo no puede exceder 120 caracteres');
        }
    }
    
    public function validateUpdate(array $data): void
    {
        if (isset($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('El correo es inválido');
        }
        
        if (isset($data['nombre']) && strlen($data['nombre']) > 120) {
            throw new ValidationException('El nombre no puede exceder 120 caracteres');
        }
        
        if (isset($data['especialidad']) && strlen($data['especialidad']) > 120) {
            throw new ValidationException('La especialidad no puede exceder 120 caracteres');
        }
        
        if (isset($data['correo']) && strlen($data['correo']) > 120) {
            throw new ValidationException('El correo no puede exceder 120 caracteres');
        }
    }
}
