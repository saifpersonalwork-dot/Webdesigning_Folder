<?php
require dirname(__DIR__) . '/config/bootstrap.php';
require dirname(__DIR__) . '/app/Core/Csrf.php';

use App\Core\Csrf;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::validate();
    echo 'Form submitted safely.';
    exit;
}
?>
<form method="post">
  <?= Csrf::field() ?>
  <label>Name <input name="name" required></label>
  <button type="submit">Submit</button>
</form>
