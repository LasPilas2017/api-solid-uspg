# API SOLID - USPG (Composer)

Proyecto PHP con **principios SOLID**, **mysqli** (XAMPP) y estructura por capas.  
Implementado para **Alumnos** y **Catedráticos** con **DTOs, Mapper y campos de auditoría**.  
Ambos recursos siguen el mismo modelo arquitectónico moderno.

---

## 🚀 Requisitos
- XAMPP (Apache + MySQL en ejecución)
- Composer instalado

---

## ⚙️ Instalación
1. Copiar a `C:\xampp\htdocs\api-solid-uspg`
2. Crear BD y tablas en **phpMyAdmin**:
   ```sql
   CREATE DATABASE IF NOT EXISTS uspg;
   USE uspg;

   CREATE TABLE IF NOT EXISTS alumnos (
     id INT AUTO_INCREMENT PRIMARY KEY,
     nombre VARCHAR(120) NOT NULL,
     email VARCHAR(120) NOT NULL UNIQUE,
     created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
     updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
     created_by VARCHAR(80) NULL,
     updated_by VARCHAR(80) NULL,
     deleted_at DATETIME NULL
   );

   CREATE TABLE IF NOT EXISTS catedraticos (
     id INT AUTO_INCREMENT PRIMARY KEY,
     nombre VARCHAR(120) NOT NULL,
     especialidad VARCHAR(120) NOT NULL,
     correo VARCHAR(120) NOT NULL UNIQUE,
     created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
     updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
     created_by VARCHAR(80) NULL,
     updated_by VARCHAR(80) NULL,
     deleted_at DATETIME NULL
   );
   ```

   **Si ya tienes la tabla catedráticos sin auditoría**, ejecuta el script `database_update.sql` para actualizarla.
3. En consola dentro del proyecto:
   ```bash
   composer dump-autoload
   ```
4. Asegura `mod_rewrite` y `AllowOverride All` en Apache (`.htaccess` ya incluido).
5. Probar en navegador o Postman:
   ```
   GET http://localhost/api-solid-uspg/public/alumnos
   ```

---

## 📦 Arquitectura (capas principales)
- **Entities** → Representan el negocio (Alumno con campos y auditoría).
- **DTOs** → Separan datos de entrada (Request) y salida (Response).
- **Mapper** → Traduce entre DTO ↔ Entidad ↔ Persistencia.
- **Repository** → Maneja SQL con mysqli, solo arrays.
- **Service** → Orquesta Mapper + Repository, expone DTOs al Controller.
- **Controller** → Recibe HTTP, construye RequestDTO y responde ResponseDTO.

---

## 🗂️ DTOs

### Alumnos
- `AlumnoRequestDTO` → usado en `POST` y `PUT`.  
  ```json
  {
    "nombre": "Juan Pérez",
    "email": "juan@example.com"
  }
  ```
- `AlumnoResponseDTO` → usado en respuestas (`GET`, `POST`, `PUT`).  
  Incluye campos de auditoría:
  ```json
  {
    "id": 1,
    "nombre": "Juan Pérez",
    "email": "juan@example.com",
    "created_at": "2025-09-12 18:30:00",
    "updated_at": null,
    "created_by": "erickF",
    "updated_by": null,
    "deleted_at": null
  }
  ```

### Catedráticos
- `CatedraticoRequestDTO` → usado en `POST` y `PUT`.  
  ```json
  {
    "nombre": "Dr. María García",
    "especialidad": "Matemáticas",
    "correo": "maria@uspg.edu"
  }
  ```
- `CatedraticoResponseDTO` → usado en respuestas (`GET`, `POST`, `PUT`).  
  Incluye campos de auditoría:
  ```json
  {
    "id": 1,
    "nombre": "Dr. María García",
    "especialidad": "Matemáticas",
    "correo": "maria@uspg.edu",
    "created_at": "2025-09-12 18:30:00",
    "updated_at": null,
    "created_by": "erickF",
    "updated_by": null,
    "deleted_at": null
  }
  ```

---

## 🌐 Endpoints

### Alumnos
- `GET    /alumnos` → lista todos (array de ResponseDTO)
- `GET    /alumnos/{id}` → uno por id
- `POST   /alumnos` → crea nuevo (usa RequestDTO)
- `PUT    /alumnos/{id}` → actualiza existente (usa RequestDTO)
- `DELETE /alumnos/{id}` → elimina (borrado duro por ahora)

#### Ejemplo: crear alumno
**Request**
```bash
POST /alumnos
Header: X-User: erickF
Content-Type: application/json

{
  "nombre": "Ana López",
  "email": "ana@example.com"
}
```

**Response**
```json
{
  "ok": true,
  "data": {
    "id": 13,
    "nombre": "Ana López",
    "email": "ana@example.com",
    "created_at": "2025-09-12 19:15:00",
    "updated_at": null,
    "created_by": "erickF",
    "updated_by": null,
    "deleted_at": null
  }
}
```

---

### Catedráticos
- `GET    /catedraticos` → lista todos (array de ResponseDTO)
- `GET    /catedraticos/{id}` → uno por id
- `POST   /catedraticos` → crea nuevo (usa RequestDTO)
- `PUT    /catedraticos/{id}` → actualiza existente (usa RequestDTO)
- `DELETE /catedraticos/{id}` → elimina (soft delete)

#### Ejemplo: crear catedrático
**Request**
```bash
POST /catedraticos
Header: X-User: erickF
Content-Type: application/json

{
  "nombre": "Dr. María García",
  "especialidad": "Matemáticas",
  "correo": "maria@uspg.edu"
}
```

**Response**
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "nombre": "Dr. María García",
    "especialidad": "Matemáticas",
    "correo": "maria@uspg.edu",
    "created_at": "2025-09-12 19:15:00",
    "updated_at": null,
    "created_by": "erickF",
    "updated_by": null,
    "deleted_at": null
  }
}
```

---

## ✅ Notas
- El **actor** (`created_by` / `updated_by`) se toma del header `X-User`. Si no se envía, se guarda como `"system"`.
- `updated_at` se actualiza automáticamente por MySQL en cada `UPDATE`.
- **Soft delete** implementado: `DELETE` marca `deleted_at` en lugar de eliminar físicamente.
- Ambas entidades (Alumnos y Catedráticos) siguen el mismo patrón arquitectónico.
