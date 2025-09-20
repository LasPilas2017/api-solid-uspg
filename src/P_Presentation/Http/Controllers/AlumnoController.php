<?php
declare(strict_types=1);

namespace App\P_Presentation\Http\Controllers;

use App\D_Domain\Services\AlumnoServiceInterface;
use App\D_Domain\DTOs\AlumnoRequestDTO;
use App\S_Shared\Errors\ValidationException;

/**
 * controlador http de alumnos
 * - acá no meto lógica de negocio ni sql
 * - armo el requestdto desde el body, llamo al service, y devuelvo responsedto
 * - dejo las respuestas con el mismo formato que ya venías usando: { ok, data }
 */
final class AlumnoController
{
    public function __construct(private AlumnoServiceInterface $service) {}

    // GET /alumnos
    public function index(): void
    {
        $data = $this->service->list(); // array de alumnoresponsedto
        $this->json(['ok' => true, 'data' => $data], 200);
    }

    // GET /alumnos/{id}
    public function show(int $id): void
    {
        $dto = $this->service->getById($id); // alumnoresponsedto | null
        if (!$dto) {
            $this->json(['ok' => false, 'data' => []], 404);
            return;
        }
        $this->json(['ok' => true, 'data' => $dto], 200);
    }

    // POST /alumnos
    public function store(): void
    {
        try {
            $body = $this->jsonInput();

            // ahora trabajamos con nombre + email (el modelo actual de alumnos)
            if (!isset($body['nombre'], $body['email'])) {
                $this->json(['ok' => false, 'data' => ['error' => 'nombre y email son requeridos']], 422);
                return;
            }

            $in = new AlumnoRequestDTO();
            $in->nombre = (string) $body['nombre'];
            $in->email  = (string) $body['email'];
            $in->actor  = $this->actor(); // x-user o "system"

            $out = $this->service->create($in); // alumnoresponsedto
            $this->json(['ok' => true, 'data' => $out], 201);
        } catch (ValidationException $e) {
            $this->json(['ok' => false, 'data' => ['error' => $e->getMessage()]], 422);
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'data' => ['error' => 'Error interno del servidor']], 500);
        }
    }

    // PUT /alumnos/{id}
    public function update(int $id): void
    {
        try {
            $body = $this->jsonInput();

            if (!isset($body['nombre'], $body['email'])) {
                $this->json(['ok' => false, 'data' => ['error' => 'nombre y email son requeridos']], 422);
                return;
            }

            $in = new AlumnoRequestDTO();
            $in->nombre = (string) $body['nombre'];
            $in->email  = (string) $body['email'];
            $in->actor  = $this->actor(); // x-user o "system"

            $out = $this->service->update($id, $in); // alumnoresponsedto | null
            if (!$out) {
                $this->json(['ok' => false, 'data' => []], 404);
                return;
            }
            $this->json(['ok' => true, 'data' => $out], 200);
        } catch (ValidationException $e) {
            $this->json(['ok' => false, 'data' => ['error' => $e->getMessage()]], 422);
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'data' => ['error' => 'Error interno del servidor']], 500);
        }
    }

    // DELETE /alumnos/{id}
    public function destroy(int $id): void
    {
        $ok = $this->service->delete($id);
        if (!$ok) {
            $this->json(['ok' => false, 'data' => []], 404);
            return;
        }
        // sin body, pero mantengo el esquema por consistencia
        $this->json(['ok' => true, 'data' => []], 204);
    }

    // ===== helpers =====

    // leo json del body y devuelvo array seguro
    private function jsonInput(): array
    {
        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    // saco el actor desde el header x-user
    private function actor(): string
    {
        return $_SERVER['HTTP_X_USER'] ?? 'system';
    }

    // respuesta json estándar
    private function json(mixed $data, int $status = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
