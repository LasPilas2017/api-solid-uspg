<?php
declare(strict_types=1);
namespace App\S_Shared\Http;
/** Helper de respuestas JSON [SRP] */
final class JsonResponse {
  private static function send(int $status, array $payload): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
  }
  public static function ok(array $data=[]): void { self::send(200, ['data'=>$data]); }
  public static function created(array $data=[]): void { self::send(201, ['data'=>$data]); }
  public static function error(string $msg, int $code=400, array $extra=[]): void {
    self::send($code, ['error'=>$msg]+$extra);
  }
  public static function notFound(): void { self::error('Ruta no encontrada', 404); }
}
