<?php
declare(strict_types=1);

namespace App\D_Domain\DTOs;

final class CatedraticoResponseDTO {
    public int $id;
    public string $nombre;
    public string $especialidad;
    public string $correo;

    // auditoría
    public string $created_at;
    public ?string $updated_at = null;
    public ?string $created_by = null;
    public ?string $updated_by = null;
    public ?string $deleted_at = null;
}
