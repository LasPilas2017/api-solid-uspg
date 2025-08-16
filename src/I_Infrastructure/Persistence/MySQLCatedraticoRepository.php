<?php
declare(strict_types=1);

namespace App\I_Infrastructure\Persistence;

use App\D_Domain\Repositories\CatedraticoRepositoryInterface;
use App\D_Domain\Entities\Catedratico;

/**
 * Repositorio MySQL para Catedratico (mysqli).
 * Columnas: id, nombre, especialidad, correo
 * [LSP][DIP][SRP]
 */
final class MySQLCatedraticoRepository implements CatedraticoRepositoryInterface {
  public function __construct(private \mysqli $db) {}

  public function all(): array {
    $res = $this->db->query("SELECT id,nombre,especialidad,correo FROM catedraticos ORDER BY id DESC");
    $out = [];
    if ($res) {
      while ($r = $res->fetch_assoc()) {
        $out[] = new Catedratico((int)$r['id'], $r['nombre'], $r['especialidad'], $r['correo']);
      }
      $res->free();
    }
    return $out;
  }

  public function find(int $id): ?Catedratico {
    $stmt = $this->db->prepare("SELECT id,nombre,especialidad,correo FROM catedraticos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $r ? new Catedratico((int)$r['id'], $r['nombre'], $r['especialidad'], $r['correo']) : null;
  }

  public function create(Catedratico $c): int {
    $stmt = $this->db->prepare("INSERT INTO catedraticos (nombre,especialidad,correo) VALUES (?,?,?)");
    $n = $c->nombre(); $e = $c->especialidad(); $co = $c->correo();
    $stmt->bind_param("sss", $n, $e, $co);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    return (int)$id;
  }

  public function update(Catedratico $c): void {
    $stmt = $this->db->prepare("UPDATE catedraticos SET nombre=?, especialidad=?, correo=? WHERE id=?");
    $n = $c->nombre(); $e = $c->especialidad(); $co = $c->correo(); $i = $c->id();
    $stmt->bind_param("sssi", $n, $e, $co, $i);
    $stmt->execute();
    $stmt->close();
  }

  public function delete(int $id): void {
    $stmt = $this->db->prepare("DELETE FROM catedraticos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
  }
}
