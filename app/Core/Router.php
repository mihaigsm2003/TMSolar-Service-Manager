<?php
declare(strict_types=1);

/**
 * Custom router for the MVC application.
 */
class Router
{
    public function dispatch(string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $basePath = rtrim(APP_PATH, '/');

        if ($basePath !== '' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        $path = '/' . trim($path, '/');
        $segments = array_values(array_filter(explode('/', trim($path, '/')), static fn ($segment): bool => $segment !== ''));

        if ($path === '/' || $path === '') {
            $controller = new InstallController();
            $controller->index();
            return;
        }

        $resource = $segments[0] ?? '';

        switch ($resource) {
            case 'install':
                $controller = new InstallController();
                $controller->index();
                break;

            case 'login':
                $controller = new AuthController();
                $controller->login();
                break;

            case 'logout':
                $controller = new AuthController();
                $controller->logout();
                break;

            case 'dashboard':
                $controller = new DashboardController();
                $controller->index();
                break;

            case 'customers':
                $controller = new CustomerController();
                $action = $segments[1] ?? 'index';
                $id = (int) ($segments[2] ?? 0);
                $this->callCustomerAction($controller, $action, $id);
                break;

            case 'manufacturers':
                $controller = new ManufacturerController();
                $action = $segments[1] ?? 'index';
                $id = (int) ($segments[2] ?? 0);
                $this->callManufacturerAction($controller, $action, $id);
                break;

            case 'device-models':
                $controller = new DeviceModelController();
                $action = $segments[1] ?? 'index';
                $id = (int) ($segments[2] ?? 0);
                $this->callDeviceModelAction($controller, $action, $id);
                break;

            case 'devices':
                $controller = new DeviceController();
                $action = $segments[1] ?? 'index';
                $id = (int) ($segments[2] ?? 0);
                $this->callDeviceAction($controller, $action, $id);
                break;

            case 'service-orders':
                $controller = new ServiceOrderController();
                $action = $segments[1] ?? 'index';
                $id = (int) ($segments[2] ?? 0);
                $this->callServiceOrderAction($controller, $action, $id);
                break;

            case 'admin':
                $controller = new AdminController();
                $action = $segments[1] ?? 'settings';
                if ($action === 'settings') {
                    $controller->settings();
                } elseif ($action === 'users') {
                    $controller->users();
                } elseif ($action === 'create-user') {
                    $controller->createUser();
                } elseif ($action === 'edit-user') {
                    $id = (int) ($segments[2] ?? 0);
                    $controller->editUser($id);
                } elseif ($action === 'delete-user') {
                    $id = (int) ($segments[2] ?? 0);
                    $controller->deleteUser($id);
                } elseif ($action === 'activity-log') {
                    $controller->activityLog();
                } else {
                    $controller->settings();
                }
                break;

            default:
                http_response_code(404);
                echo 'Page not found.';
                break;
        }
    }

    private function callCustomerAction(CustomerController $controller, string $action, int $id): void
    {
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit($id);
                break;
            case 'delete':
                $controller->delete($id);
                break;
            default:
                $controller->index();
                break;
        }
    }

    private function callManufacturerAction(ManufacturerController $controller, string $action, int $id): void
    {
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit($id);
                break;
            case 'delete':
                $controller->delete($id);
                break;
            default:
                $controller->index();
                break;
        }
    }

    private function callDeviceModelAction(DeviceModelController $controller, string $action, int $id): void
    {
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit($id);
                break;
            case 'delete':
                $controller->delete($id);
                break;
            default:
                $controller->index();
                break;
        }
    }

    private function callDeviceAction(DeviceController $controller, string $action, int $id): void
    {
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit($id);
                break;
            case 'delete':
                $controller->delete($id);
                break;
            default:
                $controller->index();
                break;
        }
    }

    private function callServiceOrderAction(ServiceOrderController $controller, string $action, int $id): void
    {
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit($id);
                break;
            case 'delete':
                $controller->delete($id);
                break;
            default:
                $controller->index();
                break;
        }
    }
}
