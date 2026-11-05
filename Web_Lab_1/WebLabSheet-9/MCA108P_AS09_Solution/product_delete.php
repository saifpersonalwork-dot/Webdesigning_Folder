<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('POST required'); }
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit('Invalid ID'); }
$stmt=$conn->prepare('DELETE FROM products WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute();
header('Location: products.php'); exit;
