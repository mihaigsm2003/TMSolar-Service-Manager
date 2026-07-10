<?php
declare(strict_types=1);

/**
 * Admin controller for settings, users, and activity log management.
 */
class AdminController extends Controller
{
    public function settings(): void
    {
        $this->requirePermission('settings');

        $db = Database::getInstance();
        $settings = [];
        foreach ($db->query('SELECT setting_key, setting_value FROM settings') as $row) {
            $settings[(string) $row['setting_key']] = (string) $row['setting_value'];
        }

        $settings = array_replace($this->getMailSettingDefaults(), $settings);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST['settings'] ?? [] as $key => $value) {
                $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)')
                    ->execute([':key' => $key, ':value' => $value]);
            }

            $this->writeMailConfigFile($_POST['settings'] ?? []);
            $this->logActivity('updated_settings', 'Updated application settings');
            header('Location: ' . UrlHelper::to('admin/settings'));
            exit;
        }

        $this->view('admin/settings', [
            'pageTitle' => 'Settings',
            'settings' => $settings,
        ]);
    }

    private function getMailSettingDefaults(): array
    {
        return [
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.yourserver.com',
            'mail_port' => '587',
            'mail_username' => 'your-email@yourdomain.com',
            'mail_password' => 'your-password',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'no-reply@yourdomain.com',
            'mail_from_name' => 'TMSolar Service Manager',
        ];
    }

    private function writeMailConfigFile(array $settings): void
    {
        $mailSettings = [
            'driver' => 'smtp',
            'host' => (string) ($settings['mail_host'] ?? 'smtp.yourserver.com'),
            'port' => (int) (($settings['mail_port'] ?? '587') ?: 587),
            'username' => (string) ($settings['mail_username'] ?? 'your-email@yourdomain.com'),
            'password' => (string) ($settings['mail_password'] ?? 'your-password'),
            'encryption' => (string) ($settings['mail_encryption'] ?? 'tls'),
            'from' => [
                'address' => (string) ($settings['mail_from_address'] ?? 'no-reply@yourdomain.com'),
                'name' => (string) ($settings['mail_from_name'] ?? 'TMSolar Service Manager'),
            ],
        ];

        $configPath = APP_ROOT . 'config/mail.php';
        $content = "<?php\nreturn [\n";
        $content .= "    'driver' => " . var_export($mailSettings['driver'], true) . ",\n";
        $content .= "    'host' => " . var_export($mailSettings['host'], true) . ",\n";
        $content .= "    'port' => " . var_export($mailSettings['port'], true) . ",\n";
        $content .= "    'username' => " . var_export($mailSettings['username'], true) . ",\n";
        $content .= "    'password' => " . var_export($mailSettings['password'], true) . ",\n";
        $content .= "    'encryption' => " . var_export($mailSettings['encryption'], true) . ",\n";
        $content .= "    'from' => [\n";
        $content .= "        'address' => " . var_export($mailSettings['from']['address'], true) . ",\n";
        $content .= "        'name' => " . var_export($mailSettings['from']['name'], true) . ",\n";
        $content .= "    ],\n";
        $content .= "];\n";

        if (!is_dir(dirname($configPath))) {
            mkdir(dirname($configPath), 0777, true);
        }

        if (file_put_contents($configPath, $content) === false) {
            throw new RuntimeException('The mail configuration file could not be written.');
        }
    }

    public function users(): void
    {
        $this->requirePermission('users');

        $userModel = new User();
        $users = $userModel->listAll();
        $this->view('admin/users', [
            'pageTitle' => 'Users',
            'users' => $users,
        ]);
    }

    public function createUser(): void
    {
        $this->requirePermission('users');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->create([
                'name' => trim((string) ($_POST['name'] ?? '')),
                'email' => trim((string) ($_POST['email'] ?? '')),
                'password' => (string) ($_POST['password'] ?? ''),
                'role' => (string) ($_POST['role'] ?? 'viewer'),
                'status' => (string) ($_POST['status'] ?? 'active'),
                'permissions' => (string) ($_POST['permissions'] ?? ''),
            ]);
            $this->logActivity('created_user', 'Created a new user account');
            header('Location: ' . UrlHelper::to('admin/users'));
            exit;
        }

        $this->view('admin/create-user', ['pageTitle' => 'Create User']);
    }

    public function activityLog(): void
    {
        $this->requirePermission('activity_log');

        $db = Database::getInstance();
        $activity = $db->query('SELECT a.*, u.name FROM activity_log a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.created_at DESC LIMIT 50')->fetchAll();

        $this->view('admin/activity-log', [
            'pageTitle' => 'Activity Log',
            'activity' => $activity,
        ]);
    }
}
