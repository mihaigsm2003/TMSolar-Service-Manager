<?php
declare(strict_types=1);

/**
 * Front controller for the TMSolar Service Manager application.
 * It bootstraps the MVC environment and delegates routing to the router.
 */

require_once __DIR__ . '/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    $cookiePath = APP_PATH === '/' ? '/' : APP_PATH;
    session_set_cookie_params(0, $cookiePath, '', false, true);
    session_start();
}
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Core/Router.php';
require_once __DIR__ . '/app/Helpers/UrlHelper.php';
require_once __DIR__ . '/app/Helpers/MailHelper.php';
require_once __DIR__ . '/app/Models/User.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/DashboardController.php';
require_once __DIR__ . '/app/Controllers/InstallController.php';
require_once __DIR__ . '/app/Controllers/CustomerController.php';
require_once __DIR__ . '/app/Controllers/ManufacturerController.php';
require_once __DIR__ . '/app/Controllers/DeviceModelController.php';
require_once __DIR__ . '/app/Controllers/DeviceController.php';
require_once __DIR__ . '/app/Controllers/ServiceOrderController.php';
require_once __DIR__ . '/app/Controllers/AdminController.php';
require_once __DIR__ . '/app/Models/Customer.php';
require_once __DIR__ . '/app/Models/Manufacturer.php';
require_once __DIR__ . '/app/Models/DeviceModel.php';
require_once __DIR__ . '/app/Models/Device.php';
require_once __DIR__ . '/app/Models/ServiceOrder.php';
require_once __DIR__ . '/install/Installer.php';

if (!isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] === '') {
    $_SERVER['REQUEST_URI'] = '/';
}

$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI']);
