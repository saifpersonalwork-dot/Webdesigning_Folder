<?php
require dirname(__DIR__) . '/config/bootstrap.php';
use App\Core\Database;

$db = Database::connection($config['db']);
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email) { exit('Enter a valid email address.'); }

$stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
    $expires = date('Y-m-d H:i:s', time() + 3600);
    $db->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at) VALUES (?, ?, ?)')
       ->execute([$user['id'], $hash, $expires]);

    // Send this URL through your configured mailer. Do not expose the token in logs.
    $resetUrl = 'https://myproject.local/reset-password.php?token=' . urlencode($token);
}
echo 'If the account exists, reset instructions will be sent.';
