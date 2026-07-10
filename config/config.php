<?php
declare(strict_types=1);

/**
 * Global application configuration for TMSolar Service Manager.
 */

$documentRoot = isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== ''
    ? realpath($_SERVER['DOCUMENT_ROOT'])
    : false;
$documentRoot = $documentRoot !== false
    ? str_replace('\\', '/', $documentRoot)
    : str_replace('\\', '/', dirname(__DIR__));

$appRoot = str_replace('\\', '/', realpath(dirname(__DIR__)) ?: dirname(__DIR__));
$documentRoot = rtrim($documentRoot, '/');
$appRoot = rtrim($appRoot, '/');

$detectedAppPath = '';
if ($documentRoot !== '' && strpos($appRoot, $documentRoot) === 0) {
    $detectedAppPath = substr($appRoot, strlen($documentRoot));
}

$detectedAppPath = '/' . trim($detectedAppPath, '/');
$detectedAppPath = $detectedAppPath === '/' ? '' : $detectedAppPath;

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'example.com';
$basePath = $detectedAppPath === '' ? '/' : $detectedAppPath . '/';

$baseUrl = $scheme . '://' . $host . $basePath;

define('APP_NAME', 'TMSolar Service Manager');
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_ROOT', $appRoot . '/');
define('APP_PATH', getenv('APP_PATH') ?: ($detectedAppPath === '' ? '/' : $detectedAppPath));
define('BASE_URL', getenv('BASE_URL') ?: $baseUrl);

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'tmsolar_service_manager');
define('DB_USER', getenv('DB_USER') ?: '');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

define('SESSION_NAME', 'tmsolar_session');
define('APP_INSTALLED', false);
define('DEFAULT_ADMIN_EMAIL', 'admin@example.com');
define('DEFAULT_ADMIN_PASSWORD', 'admin123');
