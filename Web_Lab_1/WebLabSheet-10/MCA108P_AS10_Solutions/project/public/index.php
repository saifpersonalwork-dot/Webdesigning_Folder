<?php
declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

use App\Controllers\HomeController;
use App\Controllers\ProductController;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';

try {
    if ($path === '/') {
        (new HomeController())->index();
    } elseif ($path === '/products') {
        (new ProductController())->index($config);
    } else {
        http_response_code(404);
        require dirname(__DIR__) . '/views/errors/404.php';
    }
} catch (Throwable $e) {
    error_log((string)$e);
    http_response_code(500);
    require dirname(__DIR__) . '/views/errors/500.php';
}
