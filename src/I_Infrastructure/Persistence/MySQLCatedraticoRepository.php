<?php
declare(strict_types=1);

namespace App\I_Infrastructure\Persistence;

use App\D_Domain\Repositories\CatedraticoRepositoryInterface;
use mysqli;

/**
 * repo mysql de catedráticos (mysqli)
 * - acá solo hablo en términos de arrays y sql
 * - las conversiones entidad/dto las hace el mapper, no yo
 * - incluyo columnas de auditoría en select/insert/update
 */
final class MySQLCatedraticoRepository implements CatedraticoRepositoryInterface
{
    public function __construct(private mysqli $cn) {}

    /**
     * insert: guardo un catedrático nuevo
     * espero un array con claves que coinciden con las columnas
     * uso created_at y created_by; updated_at lo gestiona mysql con on update
     */
    public function insert(array $row): int
    {
        $sql = "insert into catedraticos (nombre, especialidad, correo, created_at, created_by)
                values (?,?,?,?,?)";
        $stmt = $this->cn->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException('error al preparar insert: ' . $this->cn->error);
        }

        $stmt->bind_param(
            "sssss",
            $row['nombre'],
            $row['especialidad'],
            $row['correo'],
            $row['created_at'],
            $row['created_by']
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    /**
     * findAll: traigo todo, incluyendo auditoría
     */
    public function findAll(): array
    {
        $sql = "select id, nombre, especialidad, correo,
                       created_at, updated_at, created_by, updated_by, deleted_at
                from catedraticos
                where deleted_at is null
                order by id desc";
        $res = $this->cn->query($sql);
        if (!$res) return [];
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * findById: una fila por id, con auditoría
     */
    public function findById(int $id): ?array
    {
        $sql = "select id, nombre, especialidad, correo,
                       created_at, updated_at, created_by, updated_by, deleted_at
                from catedraticos
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
        $sql = "update catedraticos
                   set nombre = ?,
                       especialidad = ?,
                       correo = ?,
                       updated_by = ?
                 where id = ? and deleted_at is null";
        $stmt = $this->cn->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException('error al preparar update: ' . $this->cn->error);
        }

        $stmt->bind_param(
            "ssssi",
            $row['nombre'],
            $row['especialidad'],
            $row['correo'],
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
        $sql = "update catedraticos set deleted_at = now() where id = ? and deleted_at is null";
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
