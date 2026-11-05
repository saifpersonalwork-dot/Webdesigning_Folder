<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT id,name,password_hash FROM users WHERE email=?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: dashboard.php'); exit;
    }
    $message = 'Invalid email or password.';
}
?>
<h2>Login</h2><p><?= e($message) ?></p>
<form method="post">
Email: <input type="email" name="email" required><br>
Password: <input type="password" name="password" required><br>
<button type="submit">Login</button>
</form><a href="register.php">Register</a>
