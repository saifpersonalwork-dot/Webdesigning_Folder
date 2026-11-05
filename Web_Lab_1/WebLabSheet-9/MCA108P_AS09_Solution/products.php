<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_login();
$result = $conn->query('SELECT id,name,category,price FROM products ORDER BY id DESC');
?>
<h2>Products</h2><a href="product_add.php">Add product</a>
<table border="1" cellpadding="8"><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr>
<?php while ($p = $result->fetch_assoc()): ?>
<tr><td><?= (int)$p['id'] ?></td><td><?= e($p['name']) ?></td><td><?= e($p['category']) ?></td>
<td><?= e($p['price']) ?></td><td><a href="product_edit.php?id=<?= (int)$p['id'] ?>">Edit</a>
<form style="display:inline" method="post" action="product_delete.php" onsubmit="return confirm('Delete this product?')">
<input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button>Delete</button></form></td></tr>
<?php endwhile; ?></table>
