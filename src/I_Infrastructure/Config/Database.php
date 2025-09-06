<?php
declare(strict_types=1);
namespace App\I_Infrastructure\Config;
/** Conexión mysqli a 'uspg' */
final class Database {
  public static function getConexion(): \mysqli {
    $conexion = new \mysqli("localhost","root","","uspg");
    if($conexion->connect_error) throw new \RuntimeException("Conexión fallida: ".$conexion->connect_error);
    $conexion->set_charset("utf8mb4");
    return $conexion;
  }
}
