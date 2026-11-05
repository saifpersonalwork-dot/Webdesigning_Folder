<?php
// Minimal example table columns: email, failed_attempts, locked_until.
require dirname(__DIR__) . '/config/bootstrap.php';
use App\Core\Database;

$db = Database::connection($config['db']);
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
if (!$email || !is_string($password)) { exit('Invalid login details'); }

$stmt = $db->prepare('SELECT id, password_hash, failed_attempts, locked_until FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) { exit('Invalid email or password'); }
if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
    http_response_code(429);
    exit('Account temporarily locked. Try later.');
}

if (password_verify($password, $user['password_hash'])) {
    $db->prepare('UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?')->execute([$user['id']]);
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    echo 'Login successful';
} else {
    $attempts = (int)$user['failed_attempts'] + 1;
    $lockedUntil = $attempts >= 5 ? date('Y-m-d H:i:s', time() + 900) : null;
    $db->prepare('UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?')
       ->execute([$attempts, $lockedUntil, $user['id']]);
    http_response_code(401);
    exit('Invalid email or password');
}
