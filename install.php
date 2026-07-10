<?php
declare(strict_types=1);

/**
 * Standalone installer entry point.
 * It loads the installer controller directly and does not use the MVC router.
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Core/Model.php';
require_once __DIR__ . '/app/Helpers/UrlHelper.php';
require_once __DIR__ . '/app/Models/User.php';
require_once __DIR__ . '/app/Controllers/InstallController.php';
require_once __DIR__ . '/install/Installer.php';

$controller = new InstallController();
$controller->index();
