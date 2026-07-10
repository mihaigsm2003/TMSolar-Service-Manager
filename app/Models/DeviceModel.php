<?php
declare(strict_types=1);

class DeviceModel extends Model
{
    public function listAll(string $search = ''): array
    {
        $sql = 'SELECT dm.*, m.name AS manufacturer_name FROM device_models dm LEFT JOIN manufacturers m ON m.id = dm.manufacturer_id';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE dm.name LIKE :search OR m.name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY dm.created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): array|false
    {
        $statement = $this->db->prepare('SELECT * FROM device_models WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare('INSERT INTO device_models (manufacturer_id, name, description) VALUES (:manufacturer_id, :name, :description)');
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare('UPDATE device_models SET manufacturer_id = :manufacturer_id, name = :name, description = :description WHERE id = :id');
        $data[':id'] = $id;
        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM device_models WHERE id = :id');
        return $statement->execute([':id' => $id]);
    }
}
