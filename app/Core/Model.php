<?php
declare(strict_types=1);

/**
 * Base model class with shared database access.
 */
abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
