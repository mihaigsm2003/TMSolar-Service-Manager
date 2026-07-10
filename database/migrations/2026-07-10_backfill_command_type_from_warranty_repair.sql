-- Normalize existing records where command_type remained default 'Service'
-- even though warranty_repair indicates warranty service.

UPDATE service_orders
SET command_type = 'Service Garantie'
WHERE warranty_repair = 1
  AND (command_type IS NULL OR TRIM(command_type) = '' OR TRIM(command_type) = 'Service');

UPDATE service_orders
SET command_type = 'Service'
WHERE warranty_repair = 0
  AND (command_type IS NULL OR TRIM(command_type) = '');
