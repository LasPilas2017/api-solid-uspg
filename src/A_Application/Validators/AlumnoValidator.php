<?php
declare(strict_types=1);

namespace App\A_Application\Validators;

use App\S_Shared\Errors\ValidationException;

/**
 * Validador Alumno [SRP]
 */
final class AlumnoValidator
{
    public function validateCreate(array $data): void
    {
        if (empty($data['nombre'])) {
            throw new ValidationException('El nombre es requerido');
        }
        
        if (empty($data['email'])) {
            throw new ValidationException('El email es requerido');
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('El email es inválido');
        }
        
        if (strlen($data['nombre']) > 120) {
            throw new ValidationException('El nombre no puede exceder 120 caracteres');
        }
        
        if (strlen($data['email']) > 120) {
            throw new ValidationException('El email no puede exceder 120 caracteres');
        }
    }
    
    public function validateUpdate(array $data): void
    {
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('El email es inválido');
        }
        
        if (isset($data['nombre']) && strlen($data['nombre']) > 120) {
            throw new ValidationException('El nombre no puede exceder 120 caracteres');
        }
        
        if (isset($data['email']) && strlen($data['email']) > 120) {
            throw new ValidationException('El email no puede exceder 120 caracteres');
        }
    }
}