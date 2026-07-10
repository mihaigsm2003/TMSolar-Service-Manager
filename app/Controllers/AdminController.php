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
        $mailTestResult = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postedSettings = [];
            foreach ($_POST['settings'] ?? [] as $key => $value) {
                $postedSettings[(string) $key] = trim((string) $value);
            }

            if (!isset($_POST['settings']['email_notifications_enabled'])) {
                $postedSettings['email_notifications_enabled'] = '0';
            }

            $settings = array_replace($settings, $postedSettings);
            $action = (string) ($_POST['settings_action'] ?? 'save');

            if ($action === 'test_email') {
                $mailTestResult = $this->testEmailServer($settings, trim((string) ($_POST['test_email_to'] ?? '')));
            } else {
                foreach ($postedSettings as $key => $value) {
                    $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)')
                        ->execute([':key' => $key, ':value' => $value]);
                }

                $this->writeMailConfigFile($postedSettings);
                $this->logActivity('updated_settings', 'Updated application settings');
                header('Location: ' . UrlHelper::to('admin/settings'));
                exit;
            }
        }

        $this->view('admin/settings', [
            'pageTitle' => 'Settings',
            'settings' => $settings,
            'mailTestResult' => $mailTestResult,
        ]);
    }

    /**
     * @param array<string, string> $settings
     * @return array{success: bool, message: string}
     */
    private function testEmailServer(array $settings, string $requestedRecipient = ''): array
    {
        $fromAddress = trim((string) ($settings['mail_from_address'] ?? ''));
        $recipient = $requestedRecipient !== ''
            ? $requestedRecipient
            : trim((string) ($settings['support_email'] ?? $fromAddress));

        if ($fromAddress === '' || $recipient === '') {
            return [
                'success' => false,
                'message' => 'Please fill in From Email and a test recipient.',
            ];
        }
        $result = MailHelper::send(
            $recipient,
            'SMTP settings test - ' . APP_NAME,
            'This is a test email sent at ' . date('Y-m-d H:i:s') . ' to validate SMTP settings.',
            $settings
        );

        if ($result['success']) {
            $result['message'] = 'Test email sent successfully to ' . $recipient . '.';
        }

        return $result;
    }

    private function getMailSettingDefaults(): array
    {
        return [
            'email_notifications_enabled' => '1',
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
            $name = trim((string) ($_POST['name'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $role = (string) ($_POST['role'] ?? 'viewer');
            $status = (string) ($_POST['status'] ?? 'active');
            $permissions = (string) ($_POST['permissions'] ?? '');

            $userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'status' => $status,
                'permissions' => $permissions,
            ]);

            if ($email !== '') {
                $body = "Hello " . $name . ",\n\n";
                $body .= "A new account was created for you in " . APP_NAME . ".\n";
                $body .= "Role: " . $role . "\n";
                $body .= "Status: " . $status . "\n";
                $body .= "Email: " . $email . "\n";
                if ($password !== '') {
                    $body .= "Temporary password: " . $password . "\n";
                }
                $body .= "\nPlease log in and change your password if needed.\n";

                $emailResult = MailHelper::send($email, 'Your account was created - ' . APP_NAME, $body);
                if (!$emailResult['success']) {
                    $this->logActivity('email_error', 'User create email failed for ' . $email . ': ' . $emailResult['message']);
                }
            }

            $this->logActivity('created_user', 'Created a new user account');
            header('Location: ' . UrlHelper::to('admin/users'));
            exit;
        }

        $this->view('admin/create-user', ['pageTitle' => 'Create User']);
    }

    public function editUser(int $id): void
    {
        $this->requirePermission('users');

        $userModel = new User();
        $user = $userModel->find($id);
        if (!$user) {
            $this->redirect('admin/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [
                'name' => trim((string) ($_POST['name'] ?? '')),
                'email' => trim((string) ($_POST['email'] ?? '')),
                'role' => (string) ($_POST['role'] ?? 'viewer'),
                'status' => (string) ($_POST['status'] ?? 'active'),
                'permissions' => trim((string) ($_POST['permissions'] ?? '')),
                'password' => (string) ($_POST['password'] ?? ''),
            ];

            $userModel->update($id, $updateData);

            if ($updateData['email'] !== '' && $updateData['status'] !== (string) ($user['status'] ?? '')) {
                $statusBody = "Hello " . $updateData['name'] . ",\n\n";
                $statusBody .= "Your account status has been changed in " . APP_NAME . ".\n";
                $statusBody .= "Previous status: " . (string) ($user['status'] ?? 'unknown') . "\n";
                $statusBody .= "New status: " . $updateData['status'] . "\n";

                $statusEmailResult = MailHelper::send($updateData['email'], 'Account status updated - ' . APP_NAME, $statusBody);
                if (!$statusEmailResult['success']) {
                    $this->logActivity('email_error', 'User status email failed for ' . $updateData['email'] . ': ' . $statusEmailResult['message']);
                }
            }

            $this->logActivity('updated_user', 'Updated user account #' . $id);
            $this->redirect('admin/users');
        }

        $this->view('admin/edit-user', [
            'pageTitle' => 'Edit User',
            'user' => $user,
        ]);
    }

    public function deleteUser(int $id): void
    {
        $this->requirePermission('users');

        if ($id <= 0) {
            $this->redirect('admin/users');
        }

        $currentUserId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($currentUserId > 0 && $currentUserId === $id) {
            $this->redirect('admin/users');
        }

        $userModel = new User();
        $user = $userModel->find($id);
        if ($user) {
            $userEmail = trim((string) ($user['email'] ?? ''));
            if ($userEmail !== '') {
                $deleteBody = "Hello " . (string) ($user['name'] ?? 'User') . ",\n\n";
                $deleteBody .= "Your account in " . APP_NAME . " has been deleted by an administrator.\n";

                $deleteEmailResult = MailHelper::send($userEmail, 'Account deleted - ' . APP_NAME, $deleteBody);
                if (!$deleteEmailResult['success']) {
                    $this->logActivity('email_error', 'User delete email failed for ' . $userEmail . ': ' . $deleteEmailResult['message']);
                }
            }

            $userModel->delete($id);
            $this->logActivity('deleted_user', 'Deleted user account #' . $id . ' (' . (string) $user['email'] . ')');
        }

        $this->redirect('admin/users');
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
