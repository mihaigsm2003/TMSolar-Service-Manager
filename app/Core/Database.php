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

            if ($config['host'] === '' || $config['database'] === '' || $config['username'] === '') {
                throw new RuntimeException('Database is not configured. Please run the installer and provide your MySQL host, database name, username, and password.');
            }

            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['database'], $config['charset']);

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $exception) {
                throw new PDOException(
                    'Database connection failed. Check the DB host, database name, username, and password. On shared hosting, use the credentials from your hosting panel rather than root. Original error: ' . $exception->getMessage(),
                    (int) $exception->getCode(),
                    $exception
                );
            }
        }

        return self::$instance;
    }
}
