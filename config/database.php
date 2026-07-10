<?php
declare(strict_types=1);

/**
 * Database configuration helper.
 */

require_once __DIR__ . '/config.php';

/**
 * @return array<string, string>
 */
function getDatabaseConfig(): array
{
    return [
        'host' => DB_HOST,
        'database' => DB_NAME,
        'username' => DB_USER,
        'password' => DB_PASS,
        'charset' => DB_CHARSET,
    ];
}
