<?php
declare(strict_types=1);

namespace App\B_Bootstrap;

use App\I_Infrastructure\Config\Database;
use App\I_Infrastructure\Persistence\MySQLAlumnoRepository;
use App\I_Infrastructure\Persistence\MySQLCatedraticoRepository;

use App\A_Application\Mappers\AlumnoMapper;
use App\A_Application\Mappers\CatedraticoMapper;

use App\A_Application\Validators\AlumnoValidator;
use App\A_Application\Validators\CatedraticoValidator;

use App\A_Application\Services\AlumnoService;
use App\D_Domain\Services\AlumnoServiceInterface;

use App\A_Application\Services\CatedraticoService;
use App\D_Domain\Services\CatedraticoServiceInterface;

use App\D_Domain\Repositories\AlumnoRepositoryInterface;
use App\D_Domain\Repositories\CatedraticoRepositoryInterface;

use App\P_Presentation\Http\Controllers\AlumnoController;
use App\P_Presentation\Http\Controllers\CatedraticoController;

/** composition root / contenedor (dip)
 *  acá solo cableo dependencias, sin lógica extra
 */
final class Container
{
    private ?\mysqli $db = null;

    /** conexión compartida (singleton simple en el container) */
    public function db(): \mysqli
    {
        return $this->db ??= Database::getConexion();
    }

    // ===== mappers =====
    public function alumnoMapper(): AlumnoMapper
    {
        return new AlumnoMapper();
    }

    public function catedraticoMapper(): CatedraticoMapper
    {
        return new CatedraticoMapper();
    }

    // ===== validadores =====
    public function alumnoValidator(): AlumnoValidator
    {
        return new AlumnoValidator();
    }

    public function catedraticoValidator(): CatedraticoValidator
    {
        return new CatedraticoValidator();
    }

    // ===== repositorios =====
    public function alumnoRepository(): AlumnoRepositoryInterface
    {
        return new MySQLAlumnoRepository($this->db());
    }

    // dejamos catedráticos cableado, aunque aún no lo migremos
    public function catedraticoRepository(): CatedraticoRepositoryInterface
    {
        return new MySQLCatedraticoRepository($this->db());
    }

    // ===== servicios =====
    /** alumnos: ahora el service recibe repo + mapper + validator */
    public function alumnoService(): AlumnoServiceInterface
    {
        return new AlumnoService(
            $this->alumnoRepository(),
            $this->alumnoMapper(),
            $this->alumnoValidator()
        );
    }

    /** catedráticos: ahora el service recibe repo + mapper + validator */
    public function catedraticoService(): CatedraticoServiceInterface
    {
        return new CatedraticoService(
            $this->catedraticoRepository(),
            $this->catedraticoMapper(),
            $this->catedraticoValidator()
        );
    }

    // ===== controladores =====
    /** controlador http de alumnos (recibe la interface del service) */
    public function alumnoController(): AlumnoController
    {
        return new AlumnoController($this->alumnoService());
    }

    /** controlador http de catedráticos (por ahora queda igual) */
    public function catedraticoController(): CatedraticoController
    {
        return new CatedraticoController($this->catedraticoService());
    }
}
