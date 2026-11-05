<?php
require_once __DIR__.'/config/db.php'; require_once __DIR__.'/includes/helpers.php'; require_login();
header('Content-Type: application/json; charset=utf-8');
$q=trim($_GET['q']??'');
if($q===''){echo json_encode([]);exit;}
$stmt=$conn->prepare('SELECT id,name,price FROM products WHERE name LIKE ? ORDER BY name LIMIT 10');
$like='%'.$q.'%'; $stmt->bind_param('s',$like); $stmt->execute();
echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC),JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT);
