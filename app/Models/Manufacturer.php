<?php
declare(strict_types=1);

class Manufacturer extends Model
{
    public function listAll(string $search = ''): array
    {
        $sql = 'SELECT * FROM manufacturers';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE name LIKE :search OR contact_person LIKE :search OR phone LIKE :search OR email LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): array|false
    {
        $statement = $this->db->prepare('SELECT * FROM manufacturers WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare('INSERT INTO manufacturers (name, contact_person, phone, email, website) VALUES (:name, :contact_person, :phone, :email, :website)');
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare('UPDATE manufacturers SET name = :name, contact_person = :contact_person, phone = :phone, email = :email, website = :website WHERE id = :id');
        $data[':id'] = $id;
        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM manufacturers WHERE id = :id');
        return $statement->execute([':id' => $id]);
    }
}
