<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/helpers.php'; require_login();
$allowed=['name','category','price','id']; $sort=$_GET['sort']??'name';
if(!in_array($sort,$allowed,true)) $sort='name';
$dir=strtoupper($_GET['dir']??'ASC'); if(!in_array($dir,['ASC','DESC'],true)) $dir='ASC';
$sql="SELECT id,name,category,price FROM products ORDER BY `$sort` $dir";
$rows=$conn->query($sql);
function sortlink($column,$current,$direction){$next=($current===$column && $direction==='ASC')?'DESC':'ASC';return '?sort='.urlencode($column).'&dir='.$next;}
?>
<h2>Sortable Products</h2><table border="1"><tr>
<?php foreach(['id'=>'ID','name'=>'Name','category'=>'Category','price'=>'Price'] as $k=>$v): ?>
<th><a href="<?= e(sortlink($k,$sort,$dir)) ?>"><?= e($v) ?></a></th><?php endforeach; ?></tr>
<?php while($p=$rows->fetch_assoc()): ?><tr><td><?= (int)$p['id'] ?></td><td><?= e($p['name']) ?></td><td><?= e($p['category']) ?></td><td><?= e($p['price']) ?></td></tr><?php endwhile; ?></table>
