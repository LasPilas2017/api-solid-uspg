<?php
declare(strict_types=1);

namespace App\I_Infrastructure\Persistence;

use App\D_Domain\Repositories\AlumnoRepositoryInterface;
use mysqli;

/**
 * repo mysql de alumnos (mysqli)
 * - acá solo hablo en términos de arrays y sql
 * - las conversiones entidad/dto las hace el mapper, no yo
 * - incluyo columnas de auditoría en select/insert/update
 */
final class MySQLAlumnoRepository implements AlumnoRepositoryInterface
{
    public function __construct(private mysqli $cn) {}

    /**
     * insert: guardo un alumno nuevo
     * espero un array con claves que coinciden con las columnas
     * uso created_at y created_by; updated_at lo gestiona mysql con on update
     */
    public function insert(array $row): int
    {
        $sql = "insert into alumnos (nombre, email, created_at, created_by)
                values (?,?,?,?)";
        $stmt = $this->cn->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException('error al preparar insert: ' . $this->cn->error);
        }

        $stmt->bind_param(
            "ssss",
            $row['nombre'],
            $row['email'],
            $row['created_at'],
            $row['created_by']
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    /**
     * findAll: traigo todo, incluyendo auditoría (solo no eliminados)
     */
    public function findAll(): array
    {
        $sql = "select id, nombre, email,
                       created_at, updated_at, created_by, updated_by, deleted_at
                from alumnos
                where deleted_at is null
                order by id desc";
        $res = $this->cn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * findById: una fila por id, con auditoría (solo no eliminados)
     */
    public function findById(int $id): ?array
    {
        $sql = "select id, nombre, email,
                       created_at, updated_at, created_by, updated_by, deleted_at
                from alumnos
                where id = ? and deleted_at is null";
        $stmt = $this->cn->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException('error al preparar select by id: ' . $this->cn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc() ?: null;
        $stmt->close();
        return $row;
    }

    /**
     * update: actualizo datos básicos y quién actualizó
     * dejo que mysql llene updated_at con on update current_timestamp
     */
    public function update(int $id, array $row): bool
    {
        $sql = "update alumnos
                   set nombre = ?,
                       email = ?,
                       updated_by = ?
                 where id = ? and deleted_at is null";
        $stmt = $this->cn->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException('error al preparar update: ' . $this->cn->error);
        }

        $stmt->bind_param(
            "sssi",
            $row['nombre'],
            $row['email'],
            $row['updated_by'],
            $id
        );

        $ok = $stmt->execute();
        $stmt->close();
        // uso >= 0 porque puede no cambiar filas pero igual ser válido
        return $ok && $this->cn->affected_rows >= 0;
    }

    /**
     * delete: soft delete - marco como eliminado
     */
    public function delete(int $id): bool
    {
        $sql = "update alumnos set deleted_at = now() where id = ? and deleted_at is null";
        $stmt = $this->cn->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException('error al preparar delete: ' . $this->cn->error);
        }

        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok && $this->cn->affected_rows >= 0;
    }
}
