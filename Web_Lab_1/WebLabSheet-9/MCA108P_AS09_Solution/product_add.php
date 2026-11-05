<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_login();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
    $description = trim($_POST['description'] ?? '');
    if ($name === '' || $category === '' || $price === false || $price < 0) {
        $message = 'Enter valid product details.';
    } else {
        $stmt = $conn->prepare('INSERT INTO products(name,category,price,description) VALUES(?,?,?,?)');
        $stmt->bind_param('ssds', $name, $category, $price, $description);
        $stmt->execute();
        $message = 'Product added.';
    }
}
?>
<h2>Add Product</h2><p><?= e($message) ?></p>
<form method="post">
Name: <input name="name" required><br>Category: <input name="category" required><br>
Price: <input type="number" name="price" min="0" step="0.01" required><br>
Description: <textarea name="description"></textarea><br><button>Add</button>
</form><a href="products.php">Products</a>
