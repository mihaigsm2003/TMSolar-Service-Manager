<?php
declare(strict_types=1);

class Device extends Model
{
    public function listAll(string $search = ''): array
    {
        $sql = 'SELECT d.*, c.first_name, c.last_name, m.name AS manufacturer_name, dm.name AS model_name FROM devices d LEFT JOIN customers c ON c.id = d.customer_id LEFT JOIN manufacturers m ON m.id = d.manufacturer_id LEFT JOIN device_models dm ON dm.id = d.model_id';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE d.serial_number LIKE :search OR c.first_name LIKE :search OR c.last_name LIKE :search OR m.name LIKE :search OR dm.name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY d.created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): array|false
    {
        $statement = $this->db->prepare('SELECT * FROM devices WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare('INSERT INTO devices (customer_id, manufacturer_id, model_id, serial_number, warranty, purchase_date, photo) VALUES (:customer_id, :manufacturer_id, :model_id, :serial_number, :warranty, :purchase_date, :photo)');
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare('UPDATE devices SET customer_id = :customer_id, manufacturer_id = :manufacturer_id, model_id = :model_id, serial_number = :serial_number, warranty = :warranty, purchase_date = :purchase_date, photo = :photo WHERE id = :id');
        $data[':id'] = $id;
        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM devices WHERE id = :id');
        return $statement->execute([':id' => $id]);
    }
}
