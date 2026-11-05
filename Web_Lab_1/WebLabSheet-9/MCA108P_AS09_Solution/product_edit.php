<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_login();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit('Invalid product ID'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name=trim($_POST['name']??''); $category=trim($_POST['category']??'');
    $price=filter_var($_POST['price']??null,FILTER_VALIDATE_FLOAT);
    $description=trim($_POST['description']??'');
    if ($name==='' || $category==='' || $price===false || $price<0) exit('Invalid input');
    $stmt=$conn->prepare('UPDATE products SET name=?,category=?,price=?,description=? WHERE id=?');
    $stmt->bind_param('ssdsi',$name,$category,$price,$description,$id); $stmt->execute();
    header('Location: products.php'); exit;
}
$stmt=$conn->prepare('SELECT * FROM products WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute();
$p=$stmt->get_result()->fetch_assoc(); if(!$p) exit('Product not found');
?>
<h2>Edit Product</h2><form method="post"><input type="hidden" name="id" value="<?= (int)$id ?>">
Name: <input name="name" value="<?= e($p['name']) ?>" required><br>
Category: <input name="category" value="<?= e($p['category']) ?>" required><br>
Price: <input type="number" step="0.01" min="0" name="price" value="<?= e($p['price']) ?>" required><br>
Description: <textarea name="description"><?= e($p['description']) ?></textarea><br><button>Update</button></form>
