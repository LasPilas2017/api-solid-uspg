<?php
declare(strict_types=1);

/**
 * Test Runner básico para la API
 * Ejecutar con: php tests/TestRunner.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

class TestRunner
{
    private array $tests = [];
    private int $passed = 0;
    private int $failed = 0;

    public function addTest(string $name, callable $test): void
    {
        $this->tests[$name] = $test;
    }

    public function run(): void
    {
        echo "🧪 Ejecutando tests...\n\n";

        foreach ($this->tests as $name => $test) {
            echo "Test: $name\n";
            try {
                $test();
                echo "✅ PASSED\n\n";
                $this->passed++;
            } catch (\Exception $e) {
                echo "❌ FAILED: " . $e->getMessage() . "\n\n";
                $this->failed++;
            }
        }

        echo "📊 Resultados:\n";
        echo "✅ Pasaron: {$this->passed}\n";
        echo "❌ Fallaron: {$this->failed}\n";
        echo "📈 Total: " . ($this->passed + $this->failed) . "\n";
    }
}

// Tests básicos
$runner = new TestRunner();

// Test 1: Verificar que las clases se cargan correctamente
$runner->addTest('Carga de clases', function () {
    $classes = [
        'App\\D_Domain\\Entities\\Alumno',
        'App\\D_Domain\\Entities\\Catedratico',
        'App\\D_Domain\\DTOs\\AlumnoRequestDTO',
        'App\\D_Domain\\DTOs\\AlumnoResponseDTO',
        'App\\D_Domain\\DTOs\\CatedraticoRequestDTO',
        'App\\D_Domain\\DTOs\\CatedraticoResponseDTO',
        'App\\A_Application\\Mappers\\AlumnoMapper',
        'App\\A_Application\\Mappers\\CatedraticoMapper',
        'App\\A_Application\\Validators\\AlumnoValidator',
        'App\\A_Application\\Validators\\CatedraticoValidator'
    ];

    foreach ($classes as $class) {
        if (!class_exists($class)) {
            throw new \Exception("Clase $class no encontrada");
        }
    }
});

// Test 2: Verificar que los DTOs se crean correctamente
$runner->addTest('Creación de DTOs', function () {
    $alumnoRequest = new App\D_Domain\DTOs\AlumnoRequestDTO();
    $alumnoRequest->nombre = 'Juan Pérez';
    $alumnoRequest->email = 'juan@example.com';

    if ($alumnoRequest->nombre !== 'Juan Pérez') {
        throw new \Exception('DTO de Alumno no se creó correctamente');
    }

    $catedraticoRequest = new App\D_Domain\DTOs\CatedraticoRequestDTO();
    $catedraticoRequest->nombre = 'Dr. María García';
    $catedraticoRequest->especialidad = 'Matemáticas';
    $catedraticoRequest->correo = 'maria@uspg.edu';

    if ($catedraticoRequest->nombre !== 'Dr. María García') {
        throw new \Exception('DTO de Catedrático no se creó correctamente');
    }
});

// Test 3: Verificar validadores
$runner->addTest('Validadores', function () {
    $alumnoValidator = new App\A_Application\Validators\AlumnoValidator();
    $catedraticoValidator = new App\A_Application\Validators\CatedraticoValidator();

    // Test de validación exitosa
    $alumnoValidator->validateCreate([
        'nombre' => 'Juan Pérez',
        'email' => 'juan@example.com'
    ]);

    $catedraticoValidator->validateCreate([
        'nombre' => 'Dr. María García',
        'especialidad' => 'Matemáticas',
        'correo' => 'maria@uspg.edu'
    ]);

    // Test de validación fallida
    try {
        $alumnoValidator->validateCreate([
            'nombre' => '',
            'email' => 'invalid-email'
        ]);
        throw new \Exception('Validación debería haber fallado');
    } catch (App\S_Shared\Errors\ValidationException $e) {
        // Esperado
    }
});

// Test 4: Verificar mappers
$runner->addTest('Mappers', function () {
    $alumnoMapper = new App\A_Application\Mappers\AlumnoMapper();
    $catedraticoMapper = new App\A_Application\Mappers\CatedraticoMapper();

    // Test de mapper de Alumno
    $alumnoRequest = new App\D_Domain\DTOs\AlumnoRequestDTO();
    $alumnoRequest->nombre = 'Juan Pérez';
    $alumnoRequest->email = 'juan@example.com';
    $alumnoRequest->actor = 'test';

    $alumno = $alumnoMapper->fromRequestDTO($alumnoRequest);
    if ($alumno->nombre() !== 'Juan Pérez') {
        throw new \Exception('Mapper de Alumno no funciona correctamente');
    }

    // Test de mapper de Catedrático
    $catedraticoRequest = new App\D_Domain\DTOs\CatedraticoRequestDTO();
    $catedraticoRequest->nombre = 'Dr. María García';
    $catedraticoRequest->especialidad = 'Matemáticas';
    $catedraticoRequest->correo = 'maria@uspg.edu';
    $catedraticoRequest->actor = 'test';

    $catedratico = $catedraticoMapper->fromRequestDTO($catedraticoRequest);
    if ($catedratico->nombre() !== 'Dr. María García') {
        throw new \Exception('Mapper de Catedrático no funciona correctamente');
    }
});

// Ejecutar tests
$runner->run();
