<?php
declare(strict_types=1);
namespace App\B_Bootstrap;
use App\I_Infrastructure\Config\Database;
use App\I_Infrastructure\Persistence\MySQLAlumnoRepository;
use App\I_Infrastructure\Persistence\MySQLCatedraticoRepository;
use App\A_Application\Services\AlumnoService;
use App\A_Application\Services\CatedraticoService;
use App\A_Application\Validators\AlumnoValidator;
use App\A_Application\Validators\CatedraticoValidator;
use App\D_Domain\Repositories\AlumnoRepositoryInterface;
use App\D_Domain\Repositories\CatedraticoRepositoryInterface;
use App\D_Domain\Services\AlumnoServiceInterface;
use App\D_Domain\Services\CatedraticoServiceInterface;
use App\P_Presentation\Http\Controllers\AlumnoController;
use App\P_Presentation\Http\Controllers\CatedraticoController;
/** Composition Root / Contenedor [DIP] */
final class Container {
  private ?\mysqli $db = null;
  public function db(): \mysqli { return $this->db ??= Database::getConexion(); }
  // repos
  public function alumnoRepository(): AlumnoRepositoryInterface { return new MySQLAlumnoRepository($this->db()); }
  public function catedraticoRepository(): CatedraticoRepositoryInterface { return new MySQLCatedraticoRepository($this->db()); }
  // services
  public function alumnoService(): AlumnoServiceInterface { return new AlumnoService($this->alumnoRepository(), new AlumnoValidator()); }
  public function catedraticoService(): CatedraticoServiceInterface { return new CatedraticoService($this->catedraticoRepository(), new CatedraticoValidator()); }
  // controllers
  public function alumnoController(): AlumnoController { return new AlumnoController($this->alumnoService()); }
  public function catedraticoController(): CatedraticoController { return new CatedraticoController($this->catedraticoService()); }
}
