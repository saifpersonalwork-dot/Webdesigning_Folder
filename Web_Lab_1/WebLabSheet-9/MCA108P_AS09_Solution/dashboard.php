<?php
require_once __DIR__ . '/includes/helpers.php';
require_login();
?>
<h2>Dashboard</h2>
<p>Welcome, <?= e($_SESSION['user_name'] ?? 'User') ?>!</p>
<a href="products.php">View products</a> |
<a href="product_add.php">Add product</a> |
<a href="cart.php">Shopping cart</a> |
<a href="logout.php">Logout</a>
