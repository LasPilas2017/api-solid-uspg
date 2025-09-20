-- Script para actualizar la base de datos de Catedráticos
-- Ejecutar en phpMyAdmin o cliente MySQL

USE uspg;

-- Actualizar tabla catedráticos para incluir campos de auditoría
ALTER TABLE catedraticos 
ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
ADD COLUMN created_by VARCHAR(80) NULL,
ADD COLUMN updated_by VARCHAR(80) NULL,
ADD COLUMN deleted_at DATETIME NULL;

-- Actualizar registros existentes con valores por defecto
UPDATE catedraticos 
SET created_at = NOW(), 
    created_by = 'system' 
WHERE created_at IS NULL;

-- Verificar la estructura actualizada
DESCRIBE catedraticos;
