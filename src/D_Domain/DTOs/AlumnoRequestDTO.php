<?php
declare(strict_types=1);

namespace App\D_Domain\DTOs;

final class AlumnoRequestDTO {
    public string $nombre = '';
    public string $email  = '';
    public ?string $actor = null; // quién ejecuta la acción
}
