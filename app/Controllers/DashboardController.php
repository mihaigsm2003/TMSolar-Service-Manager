<?php
declare(strict_types=1);

/**
 * Dashboard controller for the main application view.
 */
class DashboardController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: ' . UrlHelper::to('login'));
            exit;
        }

        $db = Database::getInstance();
        $stats = [
            'customers' => (int) $db->query('SELECT COUNT(*) FROM customers')->fetchColumn(),
            'manufacturers' => (int) $db->query('SELECT COUNT(*) FROM manufacturers')->fetchColumn(),
            'devices' => (int) $db->query('SELECT COUNT(*) FROM devices')->fetchColumn(),
            'orders' => (int) $db->query('SELECT COUNT(*) FROM service_orders')->fetchColumn(),
            'open_orders' => (int) $db->query("SELECT COUNT(*) FROM service_orders WHERE status NOT IN ('Finalizate', 'Expediate', 'Anulate', 'Nereparabil')")->fetchColumn(),
            'recent_activity' => $db->query('SELECT a.*, u.name FROM activity_log a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.created_at DESC LIMIT 8')->fetchAll(),
        ];

        $this->view('dashboard/index', [
            'user' => $_SESSION['user'],
            'pageTitle' => 'Panou',
            'stats' => $stats,
        ]);
    }
}
