<?php
declare(strict_types=1);

/**
 * Entry point for the logout route.
 */

$_SERVER['REQUEST_URI'] = '/logout';
require_once __DIR__ . '/index.php';
