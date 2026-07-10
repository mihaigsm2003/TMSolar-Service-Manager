<?php
declare(strict_types=1);

/**
 * Lightweight PDO database wrapper.
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Create and cache a PDO connection.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = getDatabaseConfig();
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['database'], $config['charset']);

            self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return self::$instance;
    }
}
