<?php
declare(strict_types=1);

namespace App\D_Domain\DTOs;

final class CatedraticoRequestDTO {
    public string $nombre = '';
    public string $especialidad = '';
    public string $correo = '';
    public ?string $actor = null; // quién ejecuta la acción
}
