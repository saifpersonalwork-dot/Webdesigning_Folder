<?php
declare(strict_types=1);

final class CartController {
    public function __construct(private PDO $db) {}
    public function index(): void {
        require_login();
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf(); $id=(int)($_POST['id'] ?? 0); $qty=max(1,min(99,(int)($_POST['quantity'] ?? 1)));
            $action=$_POST['action'] ?? 'add';
            if ($action==='remove') unset($_SESSION['cart'][$id]);
            else $_SESSION['cart'][$id]=$qty;
        }
        $cart=$_SESSION['cart'] ?? []; $items=[]; $total=0;
        if ($cart) {
            $ids=array_keys($cart); $marks=implode(',',array_fill(0,count($ids),'?'));
            $s=$this->db->prepare("SELECT * FROM products WHERE id IN ($marks)"); $s->execute($ids);
            foreach ($s->fetchAll() as $p) { $p['quantity']=$cart[$p['id']]; $p['subtotal']=$p['price']*$p['quantity']; $total+=$p['subtotal']; $items[]=$p; }
        }
        require __DIR__.'/../views/cart.php';
    }
    public function checkout(): void {
        require_login(); verify_csrf();
        $cart=$_SESSION['cart'] ?? [];
        if (!$cart) redirect('cart');
        try {
            $this->db->beginTransaction(); $total=0; $valid=[];
            $s=$this->db->prepare('SELECT id,price FROM products WHERE id=? FOR UPDATE');
            foreach ($cart as $id=>$qty) {
                $s->execute([(int)$id]); $p=$s->fetch();
                if (!$p) throw new RuntimeException('A cart product no longer exists.');
                $valid[]=['id'=>$p['id'],'price'=>$p['price'],'qty'=>(int)$qty]; $total += $p['price']*(int)$qty;
            }
            $this->db->prepare('INSERT INTO orders(user_id,total) VALUES(?,?)')->execute([current_user()['id'],$total]);
            $orderId=(int)$this->db->lastInsertId();
            $item=$this->db->prepare('INSERT INTO order_items(order_id,product_id,quantity,unit_price) VALUES(?,?,?,?)');
            foreach ($valid as $v) $item->execute([$orderId,$v['id'],$v['qty'],$v['price']]);
            $this->db->commit(); unset($_SESSION['cart']); $message="Order #$orderId placed successfully.";
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            error_log('Checkout failed: '.$e->getMessage()); $message='Checkout failed. Please try again.';
        }
        require __DIR__.'/../views/message.php';
    }
}
