# API SOLID - USPG (Composer)
Proyecto PHP con **principios SOLID** y **mysqli** (XAMPP), recursos: alumnos y catedráticos.

## Requisitos
- XAMPP (Apache+MySQL en ejecución)
- Composer instalado

## Instalación
1. Copia a `C:\xampp\htdocs\api-solid-uspg`
2. BD y tablas (phpMyAdmin):
```sql
CREATE DATABASE IF NOT EXISTS uspg;
USE uspg;
CREATE TABLE IF NOT EXISTS alumnos (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL, email VARCHAR(120) NOT NULL UNIQUE);
CREATE TABLE IF NOT EXISTS catedraticos (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(120) NOT NULL, email VARCHAR(120) NOT NULL UNIQUE);
```
3. En consola dentro del proyecto:
```bash
composer dump-autoload
```
4. Asegura `mod_rewrite` y `AllowOverride All` en Apache. `.htaccess` ya incluido.
5. Probar:
```
GET http://localhost/api-solid-uspg/public/alumnos
```

## Endpoints
GET/POST `/alumnos`, GET/PUT/DELETE `/alumnos/{id}`  
GET/POST `/catedraticos`, GET/PUT/DELETE `/catedraticos/{id}`

Generado: 2025-08-16
