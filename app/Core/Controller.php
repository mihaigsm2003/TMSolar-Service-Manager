<?php
declare(strict_types=1);

/**
 * Base controller for common view rendering helpers.
 */
abstract class Controller
{
    /**
     * Render a view file with optional variables.
     *
     * @param array<string, mixed> $data
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = APP_ROOT . 'app/Views/' . $view . '.php';

        if (!is_file($viewFile)) {
            throw new RuntimeException('View not found: ' . $view);
        }

        require $viewFile;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: ' . UrlHelper::to('login'));
            exit;
        }
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . UrlHelper::to($path));
        exit;
    }

    protected function uploadFile(string $fieldName, string $targetDir): ?string
    {
        if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
            return null;
        }

        $file = $_FILES[$fieldName];
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] === 0) {
            return null;
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('upload_', true) . ($extension !== '' ? '.' . $extension : '');
        $destination = $targetDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return $filename;
    }

    protected function logActivity(string $action, string $details = ''): void
    {
        if (empty($_SESSION['user']['id'])) {
            return;
        }

        $db = Database::getInstance();
        $statement = $db->prepare('INSERT INTO activity_log (user_id, action, details) VALUES (:user_id, :action, :details)');
        $statement->execute([
            ':user_id' => (int) $_SESSION['user']['id'],
            ':action' => $action,
            ':details' => $details,
        ]);
    }

    protected function hasPermission(string $permission): bool
    {
        $role = $_SESSION['user']['role'] ?? 'viewer';
        $permissions = [
            'admin' => ['customers','manufacturers','devices','service_orders','settings','users','backup','activity_log'],
            'manager' => ['customers','manufacturers','devices','service_orders','activity_log'],
            'technician' => ['devices','service_orders','activity_log'],
            'viewer' => ['dashboard'],
        ];

        return in_array($permission, $permissions[$role] ?? [], true);
    }

    protected function requirePermission(string $permission): void
    {
        if (!$this->hasPermission($permission)) {
            http_response_code(403);
            echo 'Access denied.';
            exit;
        }
    }
}
