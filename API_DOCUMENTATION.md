# 📚 Documentación de la API SOLID USPG

## 🚀 Información General

- **Base URL**: `http://localhost/api-solid-uspg/public`
- **Formato**: JSON
- **Autenticación**: Header `X-User` (opcional, por defecto "system")

## 📋 Endpoints

### 👨‍🎓 Alumnos

#### GET /alumnos
Obtiene todos los alumnos (solo no eliminados).

**Respuesta:**
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "nombre": "Juan Pérez",
      "email": "juan@example.com",
      "created_at": "2025-01-12 18:30:00",
      "updated_at": null,
      "created_by": "erickF",
      "updated_by": null,
      "deleted_at": null
    }
  ]
}
```

#### GET /alumnos/{id}
Obtiene un alumno por ID.

**Respuesta:**
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "nombre": "Juan Pérez",
    "email": "juan@example.com",
    "created_at": "2025-01-12 18:30:00",
    "updated_at": null,
    "created_by": "erickF",
    "updated_by": null,
    "deleted_at": null
  }
}
```

#### POST /alumnos
Crea un nuevo alumno.

**Request:**
```json
{
  "nombre": "Ana López",
  "email": "ana@example.com"
}
```

**Headers:**
```
X-User: erickF
Content-Type: application/json
```

**Respuesta:**
```json
{
  "ok": true,
  "data": {
    "id": 2,
    "nombre": "Ana López",
    "email": "ana@example.com",
    "created_at": "2025-01-12 19:15:00",
    "updated_at": null,
    "created_by": "erickF",
    "updated_by": null,
    "deleted_at": null
  }
}
```

#### PUT /alumnos/{id}
Actualiza un alumno existente.

**Request:**
```json
{
  "nombre": "Juan Carlos Pérez",
  "email": "juan.carlos@example.com"
}
```

**Headers:**
```
X-User: erickF
Content-Type: application/json
```

**Respuesta:**
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "nombre": "Juan Carlos Pérez",
    "email": "juan.carlos@example.com",
    "created_at": "2025-01-12 18:30:00",
    "updated_at": "2025-01-12 19:20:00",
    "created_by": "erickF",
    "updated_by": "erickF",
    "deleted_at": null
  }
}
```

#### DELETE /alumnos/{id}
Elimina un alumno (soft delete).

**Respuesta:**
```json
{
  "ok": true,
  "data": []
}
```

### 👨‍🏫 Catedráticos

#### GET /catedraticos
Obtiene todos los catedráticos (solo no eliminados).

**Respuesta:**
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "nombre": "Dr. María García",
      "especialidad": "Matemáticas",
      "correo": "maria@uspg.edu",
      "created_at": "2025-01-12 18:30:00",
      "updated_at": null,
      "created_by": "erickF",
      "updated_by": null,
      "deleted_at": null
    }
  ]
}
```

#### GET /catedraticos/{id}
Obtiene un catedrático por ID.

#### POST /catedraticos
Crea un nuevo catedrático.

**Request:**
```json
{
  "nombre": "Dr. Carlos López",
  "especialidad": "Física",
  "correo": "carlos@uspg.edu"
}
```

**Headers:**
```
X-User: erickF
Content-Type: application/json
```

#### PUT /catedraticos/{id}
Actualiza un catedrático existente.

#### DELETE /catedraticos/{id}
Elimina un catedrático (soft delete).

## 🔍 Códigos de Estado HTTP

- **200 OK**: Operación exitosa
- **201 Created**: Recurso creado exitosamente
- **204 No Content**: Recurso eliminado exitosamente
- **404 Not Found**: Recurso no encontrado
- **422 Unprocessable Entity**: Error de validación
- **500 Internal Server Error**: Error interno del servidor

## ⚠️ Manejo de Errores

### Error de Validación (422)
```json
{
  "ok": false,
  "data": {
    "error": "El email es inválido"
  }
}
```

### Recurso No Encontrado (404)
```json
{
  "ok": false,
  "data": []
}
```

### Error Interno (500)
```json
{
  "ok": false,
  "data": {
    "error": "Error interno del servidor"
  }
}
```

## 🧪 Testing

Ejecutar tests básicos:
```bash
php tests/TestRunner.php
```

## 📝 Notas Importantes

1. **Soft Delete**: Los recursos eliminados se marcan con `deleted_at` pero no se eliminan físicamente.
2. **Auditoría**: Todos los recursos incluyen campos de auditoría (`created_at`, `updated_at`, `created_by`, `updated_by`).
3. **Header X-User**: Se usa para rastrear quién realiza las operaciones. Si no se envía, se usa "system".
4. **Validación**: Todos los campos son validados antes de ser procesados.
5. **CORS**: La API incluye headers CORS básicos para desarrollo.
