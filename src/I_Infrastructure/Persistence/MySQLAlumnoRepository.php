<?php
declare(strict_types=1);

namespace App\I_Infrastructure\Persistence;

use App\D_Domain\Repositories\AlumnoRepositoryInterface;

/**
 * Repositorio MySQLi que TRABAJA con ARRAYS (no entidades),
 * para acoplarse al patrón Mapper.
 *
 * Columnas: id, nombre, carnet, carrera, fecha_ingreso
 */
final class MySQLAlumnoRepository implements AlumnoRepositoryInterface
{
    public function __construct(private \mysqli $db) {}

    /**
     * Inserta un alumno.
     * Espera keys: nombre, carnet, carrera, fecha_ingreso (YYYY-MM-DD)
     */
    public function insert(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO alumnos (nombre, carnet, carrera, fecha_ingreso)
             VALUES (?, ?, ?, ?)"
        );

        $nombre        = $data['nombre']        ?? '';
        $carnet        = $data['carnet']        ?? '';
        $carrera       = $data['carrera']       ?? '';
        $fechaIngreso  = $data['fecha_ingreso'] ?? date('Y-m-d');

        $stmt->bind_param("ssss", $nombre, $carnet, $carrera, $fechaIngreso);
        $stmt->execute();

        $id = (int)$this->db->insert_id;
        $stmt->close();

        return $id;
    }

    /**
     * Devuelve todas las filas como arrays asociativos.
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(): array
    {
        $res = $this->db->query(
            "SELECT id, nombre, carnet, carrera, fecha_ingreso
             FROM alumnos
             ORDER BY id DESC"
        );

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Encontrar por ID como array asociativo.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, nombre, carnet, carrera, fecha_ingreso
             FROM alumnos WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    /**
     * Actualizar por ID usando array.
     * Espera keys: nombre, carnet, carrera, fecha_ingreso
     */
    public function updateById(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            "UPDATE alumnos
             SET nombre = ?, carnet = ?, carrera = ?, fecha_ingreso = ?
             WHERE id = ?"
        );

        $nombre       = $data['nombre']        ?? '';
        $carnet       = $data['carnet']        ?? '';
        $carrera      = $data['carrera']       ?? '';
        $fechaIngreso = $data['fecha_ingreso'] ?? date('Y-m-d');

        $stmt->bind_param("ssssi", $nombre, $carnet, $carrera, $fechaIngreso, $id);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Eliminar por ID.
     */
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM alumnos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
