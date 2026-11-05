<?php
declare(strict_types=1);

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    exit('Application configuration is missing.');
}
$config = require $configPath;

require dirname(__DIR__) . '/app/Core/Database.php';
require dirname(__DIR__) . '/app/Core/Model.php';
require dirname(__DIR__) . '/app/Models/Product.php';
require dirname(__DIR__) . '/app/Controllers/HomeController.php';
require dirname(__DIR__) . '/app/Controllers/ProductController.php';

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => (bool)$config['app']['session_secure'] && $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
