<?php
require_once __DIR__.'/config/db.php'; require_once __DIR__.'/includes/helpers.php'; require_login();
$_SESSION['cart']=$_SESSION['cart']??[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT); $action=$_POST['action']??'add';
    if($id){
        if($action==='remove') unset($_SESSION['cart'][$id]);
        elseif($action==='update'){
            $qty=max(1,min(99,(int)($_POST['quantity']??1)));
            if(isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id]=$qty;
        } else {
            $check=$conn->prepare('SELECT id FROM products WHERE id=?'); $check->bind_param('i',$id); $check->execute();
            if($check->get_result()->num_rows) $_SESSION['cart'][$id]=min(99,($_SESSION['cart'][$id]??0)+1);
        }
    }
}
$products=$conn->query('SELECT id,name,price FROM products ORDER BY name');
?>
<h2>Shopping Cart</h2><h3>Available products</h3>
<?php while($p=$products->fetch_assoc()): ?><form method="post">
<?= e($p['name']) ?> – ₹<?= e($p['price']) ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
<button name="action" value="add">Add to cart</button></form><?php endwhile; ?>
<h3>Your cart</h3>
<?php $total=0; foreach($_SESSION['cart'] as $id=>$qty):
$s=$conn->prepare('SELECT name,price FROM products WHERE id=?'); $s->bind_param('i',$id); $s->execute(); $p=$s->get_result()->fetch_assoc();
if(!$p) continue; $line=$p['price']*$qty; $total+=$line; ?>
<form method="post"><?= e($p['name']) ?> (₹<?= e($p['price']) ?>)
<input type="hidden" name="id" value="<?= (int)$id ?>">
<input type="number" name="quantity" min="1" max="99" value="<?= (int)$qty ?>">
<button name="action" value="update">Update</button><button name="action" value="remove">Remove</button>
</form>
<?php endforeach; ?>
<p>Total: ₹<?= number_format($total,2) ?></p><a href="checkout.php">Checkout</a>
