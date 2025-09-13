<?php
declare(strict_types=1);

namespace App\D_Domain\Entities;

/**
 * Entidad de dominio: Alumno
 * - Representa el estado de negocio (no sabe de HTTP ni de mysqli).
 * - Incluye campos de auditoría.
 */
final class Alumno
{
    public function __construct(
        private ?int $id,
        private string $nombre,
        private string $email,
        // Auditoría
        private string  $created_at,
        private ?string $updated_at,
        private ?string $created_by,
        private ?string $updated_by,
        private ?string $deleted_at
    ) {}

    // ===== Getters de datos =====
    public function id(): ?int         { return $this->id; }
    public function nombre(): string   { return $this->nombre; }
    public function email(): string    { return $this->email; }

    // ===== Getters de auditoría =====
    public function createdAt(): string     { return $this->created_at; }
    public function updatedAt(): ?string    { return $this->updated_at; }
    public function createdBy(): ?string    { return $this->created_by; }
    public function updatedBy(): ?string    { return $this->updated_by; }
    public function deletedAt(): ?string    { return $this->deleted_at; }

    // ===== Setters mínimos necesarios =====
    /** Permite que la capa de aplicación registre el actor que actualiza. */
    public function setUpdatedBy(?string $actor): void
    {
        $this->updated_by = $actor;
    }
}
