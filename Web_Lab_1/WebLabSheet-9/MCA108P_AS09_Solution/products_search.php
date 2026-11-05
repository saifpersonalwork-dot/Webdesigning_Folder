<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/helpers.php'; require_login();
$name='%'.trim($_GET['name']??'').'%'; $category='%'.trim($_GET['category']??'').'%';
$min=($_GET['min']??'')!==''?(float)$_GET['min']:0;
$max=($_GET['max']??'')!==''?(float)$_GET['max']:999999999;
$stmt=$conn->prepare('SELECT id,name,category,price FROM products WHERE name LIKE ? AND category LIKE ? AND price BETWEEN ? AND ? ORDER BY name');
$stmt->bind_param('ssdd',$name,$category,$min,$max); $stmt->execute(); $rows=$stmt->get_result();
?>
<h2>Search Products</h2><form><input name="name" placeholder="Product name" value="<?= e($_GET['name']??'') ?>">
<input name="category" placeholder="Category" value="<?= e($_GET['category']??'') ?>">
<input type="number" step="0.01" name="min" placeholder="Min price" value="<?= e($_GET['min']??'') ?>">
<input type="number" step="0.01" name="max" placeholder="Max price" value="<?= e($_GET['max']??'') ?>">
<button>Search</button></form>
<table border="1"><tr><th>Name</th><th>Category</th><th>Price</th></tr>
<?php while($p=$rows->fetch_assoc()): ?><tr><td><?= e($p['name']) ?></td><td><?= e($p['category']) ?></td><td><?= e($p['price']) ?></td></tr><?php endwhile; ?></table>
