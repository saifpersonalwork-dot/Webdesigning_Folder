<?php
require_once __DIR__.'/config/db.php'; require_once __DIR__.'/includes/helpers.php';
$message='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=trim($_POST['email']??''); $password=$_POST['password']??'';
    $stmt=$conn->prepare('SELECT id,name,password_hash FROM users WHERE email=?');
    $stmt->bind_param('s',$email); $stmt->execute(); $u=$stmt->get_result()->fetch_assoc();
    if($u && password_verify($password,$u['password_hash'])){
        session_regenerate_id(true); $_SESSION['user_id']=(int)$u['id']; $_SESSION['user_name']=$u['name'];
        if(!empty($_POST['remember'])){
            $token=bin2hex(random_bytes(32)); $hash=hash('sha256',$token);
            $up=$conn->prepare('UPDATE users SET remember_token_hash=? WHERE id=?'); $up->bind_param('si',$hash,$u['id']); $up->execute();
            setcookie('remember_token',$u['id'].':'.$token,['expires'=>time()+60*60*24*30,'path'=>'/','httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']),'samesite'=>'Lax']);
        }
        $message='Welcome, '.e($u['name']).'!';
    } else $message='Invalid credentials.';
}
if(empty($_SESSION['user_name']) && !empty($_COOKIE['remember_token'])){
    $parts=explode(':',$_COOKIE['remember_token'],2);
    if(count($parts)===2 && ctype_digit($parts[0])){
        $uid=(int)$parts[0]; $stmt=$conn->prepare('SELECT id,name,remember_token_hash FROM users WHERE id=?');
        $stmt->bind_param('i',$uid); $stmt->execute(); $u=$stmt->get_result()->fetch_assoc();
        if($u && hash_equals((string)$u['remember_token_hash'],hash('sha256',$parts[1]))){
            $_SESSION['user_id']=$uid; $_SESSION['user_name']=$u['name'];
        }
    }
}
?>
<h2>Remember Me Login</h2><p><?= e($message) ?></p>
<?php if(!empty($_SESSION['user_name'])): ?><p>Welcome back, <?= e($_SESSION['user_name']) ?>!</p>
<?php else: ?><form method="post">Email: <input type="email" name="email" required>
Password: <input type="password" name="password" required>
<label><input type="checkbox" name="remember" value="1"> Remember Me</label><button>Login</button></form><?php endif; ?>
