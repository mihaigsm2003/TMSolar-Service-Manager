<?php
declare(strict_types=1);

class Customer extends Model
{
    public function listAll(string $search = ''): array
    {
        $sql = 'SELECT * FROM customers';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE first_name LIKE :search OR last_name LIKE :search OR company LIKE :search OR phone LIKE :search OR email LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): array|false
    {
        $statement = $this->db->prepare('SELECT * FROM customers WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    public function create(array $data): int
    {
        $statement = $this->db->prepare('INSERT INTO customers (first_name, last_name, phone, email, company, vat_number, address) VALUES (:first_name, :last_name, :phone, :email, :company, :vat_number, :address)');
        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare('UPDATE customers SET first_name = :first_name, last_name = :last_name, phone = :phone, email = :email, company = :company, vat_number = :vat_number, address = :address WHERE id = :id');
        $data[':id'] = $id;
        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM customers WHERE id = :id');
        return $statement->execute([':id' => $id]);
    }
}
