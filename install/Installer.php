<?php
declare(strict_types=1);

/**
 * Installer for creating the initial database schema and default admin account.
 */
class Installer
{
    /**
     * Execute the installation steps.
     *
     * @param array<string, string> $input
     * @return array{success: bool, message: string}
     */
    public function run(array $input = []): array
    {
        try {
            $dbHost = trim($input['db_host'] ?? '');
            $dbName = trim($input['db_name'] ?? '');
            $dbUser = trim($input['db_user'] ?? '');
            $dbPass = (string) ($input['db_pass'] ?? '');

            if ($dbHost === '' || $dbName === '' || $dbUser === '') {
                throw new RuntimeException('Please provide the MySQL host, database name, and username.');
            }

            if ($this->isAlreadyInstalled()) {
                throw new RuntimeException('The application is already installed. Please use the existing setup.');
            }

            $pdo = $this->connect($dbHost, $dbName, $dbUser, $dbPass);

            $sql = file_get_contents(APP_ROOT . 'database/install.sql');
            if ($sql === false) {
                throw new RuntimeException('The installation SQL file could not be read.');
            }

            $pdo->exec($sql);
            $pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL UNIQUE, applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
            $pdo->exec("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES ('company_name', 'TMSolar Service Manager'), ('support_email', 'support@example.com'), ('default_priority', 'Normal'), ('backup_folder', 'storage/backups')");
            $pdo->prepare('INSERT IGNORE INTO schema_migrations (name) VALUES (?)')->execute(['initial_schema']);

            $existingUser = $this->findExistingUser($pdo);
            if (!$existingUser) {
                $this->createAdminUser($pdo);
            }

            $configPath = APP_ROOT . 'config/config.php';
            $this->writeConfigFile($configPath, $dbHost, $dbName, $dbUser, $dbPass);

            return ['success' => true, 'message' => 'Installation completed successfully.'];
        } catch (Throwable $exception) {
            return ['success' => false, 'message' => 'Installation failed: ' . $exception->getMessage()];
        }
    }

    private function connect(string $dbHost, string $dbName, string $dbUser, string $dbPass): PDO
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbName);
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $pdo;
    }

    private function isAlreadyInstalled(): bool
    {
        if (defined('APP_INSTALLED') && APP_INSTALLED) {
            return true;
        }

        $configPath = APP_ROOT . 'config/config.php';
        if (!is_file($configPath)) {
            return false;
        }

        $contents = @file_get_contents($configPath);
        if ($contents === false) {
            return false;
        }

        return str_contains($contents, "define('APP_INSTALLED', true);");
    }

    private function findExistingUser(PDO $pdo): array|false
    {
        $statement = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute([':email' => DEFAULT_ADMIN_EMAIL]);

        return $statement->fetch();
    }

    private function createAdminUser(PDO $pdo): void
    {
        $statement = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, status, permissions) VALUES (:name, :email, :password_hash, :role, :status, :permissions)');
        $statement->execute([
            ':name' => 'Administrator',
            ':email' => DEFAULT_ADMIN_EMAIL,
            ':password_hash' => password_hash(DEFAULT_ADMIN_PASSWORD, PASSWORD_DEFAULT),
            ':role' => 'admin',
            ':status' => 'active',
            ':permissions' => '',
        ]);
    }

    private function writeConfigFile(string $path, string $dbHost, string $dbName, string $dbUser, string $dbPass): void
    {
        $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== ''
            ? realpath($_SERVER['DOCUMENT_ROOT'])
            : false;
        $documentRoot = $documentRoot !== false ? str_replace('\\', '/', $documentRoot) : '';
        $appRoot = str_replace('\\', '/', realpath(APP_ROOT) ?: APP_ROOT);

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
        $appPath = $detectedAppPath === '' ? '/' : $detectedAppPath;

        $configContents = <<<PHP
<?php
declare(strict_types=1);

/**
 * Generated application configuration for shared hosting.
 */

define('APP_NAME', 'TMSolar Service Manager');
define('APP_ENV', 'production');
define('APP_ROOT', __DIR__ . '/../');
define('APP_PATH', '$appPath');
define('BASE_URL', '$baseUrl');
define('DB_HOST', '$dbHost');
define('DB_NAME', '$dbName');
define('DB_USER', '$dbUser');
define('DB_PASS', '$dbPass');
define('DB_CHARSET', 'utf8mb4');
define('SESSION_NAME', 'tmsolar_session');
define('APP_INSTALLED', true);
define('DEFAULT_ADMIN_EMAIL', 'admin@example.com');
define('DEFAULT_ADMIN_PASSWORD', 'admin123');
PHP;

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        if (file_put_contents($path, $configContents) === false) {
            throw new RuntimeException('The configuration file could not be written. Please make sure the config folder is writable.');
        }
    }
}
