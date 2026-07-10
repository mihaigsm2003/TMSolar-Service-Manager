<?php
declare(strict_types=1);

/**
 * Legacy-compatible entry point for public service order submission.
 */

$_SERVER['REQUEST_URI'] = '/comanda-service';
require_once __DIR__ . '/index.php';
