<?php
declare(strict_types=1);

/**
 * User model for authentication and basic user management.
 */
class User extends Model
{
    /**
     * Create a new user record.
     */
    public function create(array $data): int
    {
        $statement = $this->db->prepare('INSERT INTO users (name, email, password_hash, role, status, permissions) VALUES (:name, :email, :password_hash, :role, :status, :permissions)');
        $statement->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role'] ?? 'admin',
            ':status' => $data['status'] ?? 'active',
            ':permissions' => $data['permissions'] ?? '',
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Find a user by email.
     *
     * @return array<string, mixed>|false
     */
    public function findByEmail(string $email)
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute([':email' => $email]);

        return $statement->fetch();
    }

    /**
     * Verify a password for a given user.
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function listAll(): array
    {
        $statement = $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|false
     */
    public function find(int $id)
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);

        return $statement->fetch();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE users SET name = :name, email = :email, role = :role, status = :status, permissions = :permissions';
        $params = [
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':role' => $data['role'],
            ':status' => $data['status'],
            ':permissions' => $data['permissions'],
            ':id' => $id,
        ];

        if (!empty($data['password'])) {
            $sql .= ', password_hash = :password_hash';
            $params[':password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = :id';

        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $statement->execute([':id' => $id]);
    }
}
