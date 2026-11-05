<?php
namespace App\Controllers;

use App\Models\Product;
use App\Core\Database;

class ProductController
{
    public function index(array $config): void
    {
        $model = new Product(Database::connection($config['db']));
        $products = $model->all();
        $pageTitle = 'Products';
        require dirname(__DIR__, 2) . '/views/products/index.php';
    }
}
