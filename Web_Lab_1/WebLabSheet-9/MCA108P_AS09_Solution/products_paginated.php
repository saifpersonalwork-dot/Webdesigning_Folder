<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/helpers.php'; require_login();
$perPage=5; $page=max(1,(int)($_GET['page']??1)); $offset=($page-1)*$perPage;
$total=(int)$conn->query('SELECT COUNT(*) AS n FROM products')->fetch_assoc()['n'];
$pages=max(1,(int)ceil($total/$perPage));
$stmt=$conn->prepare('SELECT id,name,category,price FROM products ORDER BY id DESC LIMIT ? OFFSET ?');
$stmt->bind_param('ii',$perPage,$offset); $stmt->execute(); $rows=$stmt->get_result();
?>
<h2>Paginated Products</h2><table border="1"><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th></tr>
<?php while($p=$rows->fetch_assoc()): ?><tr><td><?= (int)$p['id'] ?></td><td><?= e($p['name']) ?></td><td><?= e($p['category']) ?></td><td><?= e($p['price']) ?></td></tr><?php endwhile; ?></table>
<?php if($page>1): ?><a href="?page=<?= $page-1 ?>">Previous</a><?php endif; ?>
<?php for($i=1;$i<=$pages;$i++): ?> <a href="?page=<?= $i ?>"><?= $i ?></a> <?php endfor; ?>
<?php if($page<$pages): ?><a href="?page=<?= $page+1 ?>">Next</a><?php endif; ?>
