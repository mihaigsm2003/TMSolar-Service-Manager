<?php
declare(strict_types=1);

/**
 * Small helper for building application URLs.
 */
class UrlHelper
{
    public static function to(string $path = ''): string
    {
        $baseUrl = self::baseUrl();
        $relativePath = ltrim($path, '/');

        if ($relativePath === '') {
            return $baseUrl;
        }

        return rtrim($baseUrl, '/') . '/' . $relativePath;
    }

    public static function asset(string $path = ''): string
    {
        return self::to($path);
    }

    public static function image(string $path = ''): string
    {
        return self::asset($path);
    }

    private static function baseUrl(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptDir = dirname($scriptName);
        $basePath = '/';

        if ($scriptName !== '' && $scriptDir !== '.' && $scriptDir !== '/') {
            $basePath = rtrim($scriptDir, '/') . '/';
        } elseif ($scriptName !== '' && $scriptDir === '/') {
            $basePath = '/';
        }

        if (strpos($scriptName, '/index.php') !== false || strpos($scriptName, '/install.php') !== false || strpos($scriptName, '/login.php') !== false) {
            $basePath = dirname($scriptName);
            $basePath = $basePath === '/' ? '/' : rtrim($basePath, '/') . '/';
        }

        if ($basePath !== '/' && strpos($basePath, '/') !== 0) {
            $basePath = '/' . $basePath;
        }

        return $scheme . '://' . $host . $basePath;
    }
}
