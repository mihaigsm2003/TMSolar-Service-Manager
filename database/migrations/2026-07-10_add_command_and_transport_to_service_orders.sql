-- Adds dedicated fields used by Comanda Online and backoffice forms.
ALTER TABLE service_orders
    ADD COLUMN command_type VARCHAR(50) DEFAULT 'Service' AFTER device_id,
    ADD COLUMN transport_type VARCHAR(30) DEFAULT 'Curier' AFTER command_type;

-- Backfill old rows from warranty_repair where possible.
UPDATE service_orders
SET command_type = CASE
    WHEN warranty_repair = 1 THEN 'Service Garantie'
    ELSE 'Service'
END
WHERE command_type IS NULL OR command_type = '';
