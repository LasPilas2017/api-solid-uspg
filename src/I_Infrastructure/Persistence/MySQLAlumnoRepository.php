<?php
declare(strict_types=1);

namespace App\I_Infrastructure\Persistence;

use App\D_Domain\Repositories\AlumnoRepositoryInterface;
use App\D_Domain\Entities\Alumno;

/**
 * Repositorio MySQL (mysqli) para Alumno usando columnas:
 * id, nombre, carnet, carrera, fecha_ingreso
 * [LSP][DIP][SRP]
 */
final class MySQLAlumnoRepository implements AlumnoRepositoryInterface {
  public function __construct(private \mysqli $db) {}

  public function all(): array {
    $res = $this->db->query("SELECT id,nombre,carnet,carrera,fecha_ingreso FROM alumnos ORDER BY id DESC");
    $out = [];
    if ($res) {
      while ($r = $res->fetch_assoc()) {
        $out[] = new Alumno((int)$r['id'], $r['nombre'], $r['carnet'], $r['carrera'], $r['fecha_ingreso']);
      }
      $res->free();
    }
    return $out;
  }

  public function find(int $id): ?Alumno {
    $stmt = $this->db->prepare("SELECT id,nombre,carnet,carrera,fecha_ingreso FROM alumnos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $r ? new Alumno((int)$r['id'], $r['nombre'], $r['carnet'], $r['carrera'], $r['fecha_ingreso']) : null;
  }

  public function create(Alumno $alumno): int {
    $stmt = $this->db->prepare("INSERT INTO alumnos (nombre,carnet,carrera,fecha_ingreso) VALUES (?,?,?,?)");
    $n = $alumno->nombre(); $c = $alumno->carnet(); $ca = $alumno->carrera(); $f = $alumno->fechaIngreso();
    $stmt->bind_param("ssss", $n, $c, $ca, $f);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    return (int)$id;
  }

  public function update(Alumno $alumno): void {
    $stmt = $this->db->prepare("UPDATE alumnos SET nombre=?, carnet=?, carrera=?, fecha_ingreso=? WHERE id=?");
    $n = $alumno->nombre(); $c = $alumno->carnet(); $ca = $alumno->carrera(); $f = $alumno->fechaIngreso(); $i = $alumno->id();
    $stmt->bind_param("ssssi", $n, $c, $ca, $f, $i);
    $stmt->execute();
    $stmt->close();
  }

  public function delete(int $id): void {
    $stmt = $this->db->prepare("DELETE FROM alumnos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
  }
}
