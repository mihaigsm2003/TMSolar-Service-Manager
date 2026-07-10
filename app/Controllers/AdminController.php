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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST['settings'] ?? [] as $key => $value) {
                $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)')
                    ->execute([':key' => $key, ':value' => $value]);
            }
            $this->logActivity('updated_settings', 'Updated application settings');
            header('Location: ' . UrlHelper::to('admin/settings'));
            exit;
        }

        $this->view('admin/settings', [
            'pageTitle' => 'Settings',
            'settings' => $settings,
        ]);
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
