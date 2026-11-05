<?php
require_once __DIR__.'/includes/helpers.php';
$comment=$_POST['comment']??'';
?>
<h2>XSS Protection Demo</h2>
<form method="post"><textarea name="comment"><?= e($comment) ?></textarea><button>Show safely</button></form>
<p>Output: <?= e($comment) ?></p>
<p>htmlspecialchars() converts HTML special characters so user input is displayed as text.</p>
