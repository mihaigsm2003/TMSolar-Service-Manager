<?php
declare(strict_types=1);

/**
 * Entry point alias for public online order route.
 */

$_SERVER['REQUEST_URI'] = '/comanda-online';
require_once __DIR__ . '/index.php';
