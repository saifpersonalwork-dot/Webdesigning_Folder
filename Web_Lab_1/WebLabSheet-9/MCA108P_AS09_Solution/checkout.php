<?php
require_once __DIR__.'/config/db.php'; require_once __DIR__.'/includes/helpers.php'; require_login();
$cart=$_SESSION['cart']??[]; if(!$cart) exit('Cart is empty. <a href="cart.php">Back</a>');
try {
    $conn->begin_transaction();
    $items=[]; $total=0.0;
    $get=$conn->prepare('SELECT price FROM products WHERE id=? FOR UPDATE');
    foreach($cart as $id=>$qty){
        $id=(int)$id; $qty=max(1,min(99,(int)$qty));
        $get->bind_param('i',$id); $get->execute(); $row=$get->get_result()->fetch_assoc();
        if(!$row) throw new RuntimeException('A product is no longer available.');
        $price=(float)$row['price']; $total += $price*$qty; $items[]=[$id,$qty,$price];
    }
    $userId=(int)$_SESSION['user_id'];
    $order=$conn->prepare("INSERT INTO orders(user_id,total,status) VALUES(?,?,'placed')");
    $order->bind_param('id',$userId,$total); $order->execute(); $orderId=$conn->insert_id;
    $insert=$conn->prepare('INSERT INTO order_items(order_id,product_id,quantity,unit_price) VALUES(?,?,?,?)');
    foreach($items as [$productId,$qty,$price]){
        $insert->bind_param('iiid',$orderId,$productId,$qty,$price); $insert->execute();
    }
    $conn->commit(); unset($_SESSION['cart']);
    echo 'Order placed successfully. Order ID: '.(int)$orderId;
} catch(Throwable $e) {
    $conn->rollback(); error_log($e->getMessage()); http_response_code(500);
    echo 'Checkout failed. No order was placed.';
}
