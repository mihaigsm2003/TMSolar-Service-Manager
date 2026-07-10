<?php
declare(strict_types=1);

class ServiceOrder extends Model
{
    public function listAll(string $search = ''): array
    {
        $sql = 'SELECT so.*, c.first_name, c.last_name, d.serial_number FROM service_orders so LEFT JOIN customers c ON c.id = so.customer_id LEFT JOIN devices d ON d.id = so.device_id';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE so.order_number LIKE :search OR c.first_name LIKE :search OR c.last_name LIKE :search OR d.serial_number LIKE :search OR so.reported_fault LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY so.created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): array|false
    {
        $statement = $this->db->prepare('SELECT * FROM service_orders WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare('INSERT INTO service_orders (order_number, received_date, customer_id, device_id, accessories, reported_fault, diagnosed_fault, repair_notes, internal_notes, priority, status, repair_cost, labour_cost, shipping_cost, total_cost, warranty_repair, photos, documents) VALUES (:order_number, :received_date, :customer_id, :device_id, :accessories, :reported_fault, :diagnosed_fault, :repair_notes, :internal_notes, :priority, :status, :repair_cost, :labour_cost, :shipping_cost, :total_cost, :warranty_repair, :photos, :documents)');
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare('UPDATE service_orders SET order_number = :order_number, received_date = :received_date, customer_id = :customer_id, device_id = :device_id, accessories = :accessories, reported_fault = :reported_fault, diagnosed_fault = :diagnosed_fault, repair_notes = :repair_notes, internal_notes = :internal_notes, priority = :priority, status = :status, repair_cost = :repair_cost, labour_cost = :labour_cost, shipping_cost = :shipping_cost, total_cost = :total_cost, warranty_repair = :warranty_repair, photos = :photos, documents = :documents WHERE id = :id');
        $data[':id'] = $id;
        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM service_orders WHERE id = :id');
        return $statement->execute([':id' => $id]);
    }
}
