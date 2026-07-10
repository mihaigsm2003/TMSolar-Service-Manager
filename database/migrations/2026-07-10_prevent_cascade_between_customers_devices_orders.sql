-- Prevent cascading deletions between customers, devices, and service orders.
-- After this migration:
-- - deleting a customer will be blocked if related devices/orders exist
-- - deleting an order will never delete the related customer

ALTER TABLE service_orders
    DROP FOREIGN KEY fk_service_orders_customer,
    ADD CONSTRAINT fk_service_orders_customer
        FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE RESTRICT;

ALTER TABLE service_orders
    DROP FOREIGN KEY fk_service_orders_device,
    ADD CONSTRAINT fk_service_orders_device
        FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE RESTRICT;

ALTER TABLE devices
    DROP FOREIGN KEY fk_devices_customer,
    ADD CONSTRAINT fk_devices_customer
        FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE RESTRICT;
