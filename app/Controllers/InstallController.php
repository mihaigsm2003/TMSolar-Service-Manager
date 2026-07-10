<?php
declare(strict_types=1);

/**
 * Installation controller with a simple setup flow.
 */
class InstallController extends Controller
{
    public function index(): void
    {
        if (defined('APP_INSTALLED') && APP_INSTALLED) {
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $installer = new Installer();
            $result = $installer->run([
                'db_host' => trim((string) ($_POST['db_host'] ?? '')),
                'db_name' => trim((string) ($_POST['db_name'] ?? '')),
                'db_user' => trim((string) ($_POST['db_user'] ?? '')),
                'db_pass' => (string) ($_POST['db_pass'] ?? ''),
            ]);

            if ($result['success']) {
                header('Location: ' . UrlHelper::to('login'));
                exit;
            }

            $this->view('install/index', [
                'error' => $result['message'],
                'db_host' => $_POST['db_host'] ?? '',
                'db_name' => $_POST['db_name'] ?? '',
                'db_user' => $_POST['db_user'] ?? '',
            ]);
            return;
        }

        $this->view('install/index', [
            'db_host' => '',
            'db_name' => '',
            'db_user' => '',
        ]);
    }
}
