<?php
declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    $cookiePath = APP_PATH === '/' ? '/' : APP_PATH;
    session_set_cookie_params(0, $cookiePath, '', false, true);
    session_start();
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Helpers/UrlHelper.php';

$pageTitle = 'About';
$contentView = __DIR__ . '/app/Views/about_content.php';

require __DIR__ . '/app/Views/layout.php';
