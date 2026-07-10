-- Normalize existing records where transport_type remained default/empty.
-- Uses internal notes written by online-order flow when available.

UPDATE service_orders
SET transport_type = 'Personal'
WHERE (transport_type IS NULL OR TRIM(transport_type) = '' OR TRIM(transport_type) = 'Curier')
  AND internal_notes LIKE '%Transport: Personal%';

UPDATE service_orders
SET transport_type = 'Curier'
WHERE (transport_type IS NULL OR TRIM(transport_type) = '')
  AND internal_notes LIKE '%Transport: Curier%';
