<?php
declare(strict_types=1);
$config=require __DIR__.'/../config/config.php';
ini_set('display_errors','0'); ini_set('log_errors','1');
ini_set('error_log',__DIR__.'/../storage/logs/php-error.log');
session_set_cookie_params(['lifetime'=>0,'path'=>'/','httponly'=>true,'secure'=>$config['session_secure'],'samesite'=>'Lax']);
session_start();
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../app/helpers.php';
require_once __DIR__.'/../app/models/Product.php';
if (file_exists(__DIR__.'/../vendor/autoload.php')) require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../app/controllers/AuthController.php';
require_once __DIR__.'/../app/controllers/ProductController.php';
require_once __DIR__.'/../app/controllers/CartController.php';
require_once __DIR__.'/../app/controllers/ApiController.php';
$auth=new AuthController($db,$config); $product=new ProductController($db,$config); $cart=new CartController($db); $api=new ApiController($db);

$route=$_GET['route'] ?? 'dashboard';
try {
    switch ($route) {
        case 'register': $auth->register(); break;
        case 'login': $auth->login(); break;
        case 'logout': $auth->logout(); break;
        case 'forgot': $auth->forgot(); break;
        case 'reset': $auth->reset(); break;
        case 'dashboard': require_login(); require __DIR__.'/../app/views/dashboard.php'; break;
        case 'products': $product->index(); break;
        case 'product-form': $product->form(); break;
        case 'product-delete': $product->delete(); break;
        case 'products/suggest': $product->suggest(); break;
        case 'cart': $cart->index(); break;
        case 'checkout': $cart->checkout(); break;
        case 'api/products': $api->products(); break;
        default: require __DIR__.'/../app/views/errors/404.php';
    }
} catch (Throwable $e) {
    error_log($e->__toString());
    require __DIR__.'/../app/views/errors/500.php';
}
