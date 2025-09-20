<?php
declare(strict_types=1);

namespace App\P_Presentation\Http\Controllers;

use App\D_Domain\Services\CatedraticoServiceInterface;
use App\D_Domain\DTOs\CatedraticoRequestDTO;
use App\S_Shared\Errors\ValidationException;

/**
 * controlador http de catedráticos
 * - acá no meto lógica de negocio ni sql
 * - armo el requestdto desde el body, llamo al service, y devuelvo responsedto
 * - dejo las respuestas con el mismo formato que ya venías usando: { ok, data }
 */
final class CatedraticoController
{
    public function __construct(private CatedraticoServiceInterface $service) {}

    // GET /catedraticos
    public function index(): void
    {
        $data = $this->service->list(); // array de catedraticoresponsedto
        $this->json(['ok' => true, 'data' => $data], 200);
    }

    // GET /catedraticos/{id}
    public function show(int $id): void
    {
        $dto = $this->service->getById($id); // catedraticoresponsedto | null
        if (!$dto) {
            $this->json(['ok' => false, 'data' => []], 404);
            return;
        }
        $this->json(['ok' => true, 'data' => $dto], 200);
    }

    // POST /catedraticos
    public function store(): void
    {
        try {
            $body = $this->jsonInput();

            // ahora trabajamos con nombre + especialidad + correo
            if (!isset($body['nombre'], $body['especialidad'], $body['correo'])) {
                $this->json(['ok' => false, 'data' => ['error' => 'nombre, especialidad y correo son requeridos']], 422);
                return;
            }

            $in = new CatedraticoRequestDTO();
            $in->nombre = (string) $body['nombre'];
            $in->especialidad = (string) $body['especialidad'];
            $in->correo = (string) $body['correo'];
            $in->actor = $this->actor(); // x-user o "system"

            $out = $this->service->create($in); // catedraticoresponsedto
            $this->json(['ok' => true, 'data' => $out], 201);
        } catch (ValidationException $e) {
            $this->json(['ok' => false, 'data' => ['error' => $e->getMessage()]], 422);
        } catch (\Exception $e) {
            $this->json(['ok' => false, 'data' => ['error' => 'Error interno del servidor']], 500);
        }
    }

    // PUT /catedraticos/{id}
    public function update(int $id): void
    {
        try {
            $body = $this->jsonInput();

            if (!isset($body['nombre'], $body['especialidad'], $body['correo'])) {
                $this->json(['ok' => false, 'data' => ['error' => 'nombre, especialidad y correo son requeridos']], 422);
                return;
            }

            $in = new CatedraticoRequestDTO();
            $in->nombre = (string) $body['nombre'];
            $in->especialidad = (string) $body['especialidad'];
            $in->correo = (string) $body['correo'];
            $in->actor = $this->actor(); // x-user o "system"

            $out = $this->service->update($id, $in); // catedraticoresponsedto | null
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

    // DELETE /catedraticos/{id}
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
