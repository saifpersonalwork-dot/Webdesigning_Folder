<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    if ($name === '' || !$email || strlen($password) < 8) {
        $message = 'Enter a name, valid email, and password of at least 8 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare('INSERT INTO users (name,email,password_hash) VALUES (?,?,?)');
            $stmt->bind_param('sss', $name, $email, $hash);
            $stmt->execute();
            $message = 'Registration successful. You can now log in.';
        } catch (mysqli_sql_exception $ex) {
            $message = $ex->getCode() === 1062 ? 'Email already registered.' : 'Registration failed.';
        }
    }
}
?>
<h2>User Registration</h2><p><?= e($message) ?></p>
<form method="post">
Name: <input name="name" required maxlength="100"><br>
Email: <input type="email" name="email" required><br>
Password: <input type="password" name="password" minlength="8" required><br>
<button type="submit">Register</button>
</form><a href="login.php">Login</a>
