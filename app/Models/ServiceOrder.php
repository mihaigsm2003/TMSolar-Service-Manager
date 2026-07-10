<?php
declare(strict_types=1);

class ServiceOrder extends Model
{
    /** @var array<string, bool> */
    private array $columnCache = [];

    public function listAll(string $search = ''): array
    {
        $sql = 'SELECT so.*, c.first_name, c.last_name, d.serial_number, d.purchase_date AS warranty_repair_date, dm.name AS device_model_name FROM service_orders so LEFT JOIN customers c ON c.id = so.customer_id LEFT JOIN devices d ON d.id = so.device_id LEFT JOIN device_models dm ON dm.id = d.model_id';
        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE so.order_number LIKE :search OR c.first_name LIKE :search OR c.last_name LIKE :search OR d.serial_number LIKE :search OR dm.name LIKE :search OR so.reported_fault LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        $sql .= ' ORDER BY so.created_at DESC';

        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): array|false
    {
        $statement = $this->db->prepare('SELECT so.*, d.serial_number, d.purchase_date AS warranty_repair_date, dm.name AS device_model_name FROM service_orders so LEFT JOIN devices d ON d.id = so.device_id LEFT JOIN device_models dm ON dm.id = d.model_id WHERE so.id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        return $statement->fetch();
    }

    public function create(array $data): int
    {
        $hasCommandType = $this->hasColumn('command_type');
        $hasTransportType = $this->hasColumn('transport_type');

        $columns = [
            'order_number',
            'received_date',
            'customer_id',
            'device_id',
        ];
        $placeholders = [
            ':order_number',
            ':received_date',
            ':customer_id',
            ':device_id',
        ];

        if ($hasCommandType) {
            $columns[] = 'command_type';
            $placeholders[] = ':command_type';
        } else {
            unset($data[':command_type']);
        }

        if ($hasTransportType) {
            $columns[] = 'transport_type';
            $placeholders[] = ':transport_type';
        } else {
            $data[':internal_notes'] = $this->upsertInternalNotesField(
                (string) ($data[':internal_notes'] ?? ''),
                'Transport',
                (string) ($data[':transport_type'] ?? '')
            );
            unset($data[':transport_type']);
        }

        $tailColumns = [
            'accessories',
            'reported_fault',
            'diagnosed_fault',
            'repair_notes',
            'internal_notes',
            'priority',
            'status',
            'repair_cost',
            'labour_cost',
            'shipping_cost',
            'total_cost',
            'warranty_repair',
            'photos',
            'documents',
        ];

        foreach ($tailColumns as $column) {
            $columns[] = $column;
            $placeholders[] = ':' . $column;
        }

        $statement = $this->db->prepare(
            'INSERT INTO service_orders (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')'
        );

        $statement->execute($data);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $hasCommandType = $this->hasColumn('command_type');
        $hasTransportType = $this->hasColumn('transport_type');

        $sets = [
            'order_number = :order_number',
            'received_date = :received_date',
            'customer_id = :customer_id',
            'device_id = :device_id',
        ];

        if ($hasCommandType) {
            $sets[] = 'command_type = :command_type';
        } else {
            unset($data[':command_type']);
        }

        if ($hasTransportType) {
            $sets[] = 'transport_type = :transport_type';
        } else {
            $data[':internal_notes'] = $this->upsertInternalNotesField(
                (string) ($data[':internal_notes'] ?? ''),
                'Transport',
                (string) ($data[':transport_type'] ?? '')
            );
            unset($data[':transport_type']);
        }

        $sets = array_merge($sets, [
            'accessories = :accessories',
            'reported_fault = :reported_fault',
            'diagnosed_fault = :diagnosed_fault',
            'repair_notes = :repair_notes',
            'internal_notes = :internal_notes',
            'priority = :priority',
            'status = :status',
            'repair_cost = :repair_cost',
            'labour_cost = :labour_cost',
            'shipping_cost = :shipping_cost',
            'total_cost = :total_cost',
            'warranty_repair = :warranty_repair',
            'photos = :photos',
            'documents = :documents',
        ]);

        $statement = $this->db->prepare('UPDATE service_orders SET ' . implode(', ', $sets) . ' WHERE id = :id');

        $data[':id'] = $id;
        return $statement->execute($data);
    }

    private function hasColumn(string $columnName): bool
    {
        if (array_key_exists($columnName, $this->columnCache)) {
            return $this->columnCache[$columnName];
        }

        try {
            $statement = $this->db->prepare('SHOW COLUMNS FROM service_orders LIKE :column_name');
            $statement->execute([':column_name' => $columnName]);
            $this->columnCache[$columnName] = (bool) $statement->fetch();
        } catch (Throwable $exception) {
            $this->columnCache[$columnName] = false;
        }

        return $this->columnCache[$columnName];
    }

    private function upsertInternalNotesField(string $notes, string $label, string $value): string
    {
        $normalizedValue = trim($value);
        if ($normalizedValue === '') {
            return $notes;
        }

        $pattern = '/^' . preg_quote($label, '/') . ':\s*.*$/mi';
        if (preg_match($pattern, $notes) === 1) {
            return (string) preg_replace($pattern, $label . ': ' . $normalizedValue, $notes);
        }

        $trimmedNotes = trim($notes);
        if ($trimmedNotes === '') {
            return $label . ': ' . $normalizedValue;
        }

        return $trimmedNotes . "\n" . $label . ': ' . $normalizedValue;
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM service_orders WHERE id = :id');
        return $statement->execute([':id' => $id]);
    }
}
