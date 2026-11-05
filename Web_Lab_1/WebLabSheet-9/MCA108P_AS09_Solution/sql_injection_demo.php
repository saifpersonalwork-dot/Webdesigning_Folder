<?php
require_once __DIR__.'/config/db.php'; require_once __DIR__.'/includes/helpers.php'; require_login();
$term=$_GET['name']??'';
echo '<h3>Unsafe example (do not use)</h3><pre>$sql = "SELECT * FROM products WHERE name = \\'".$term."\\'";</pre>';
echo '<p>The unsafe query concatenates user input and can be manipulated.</p>';
echo '<h3>Safe prepared-statement example</h3>';
$stmt=$conn->prepare('SELECT id,name,category,price FROM products WHERE name LIKE ?');
$like='%'.$term.'%'; $stmt->bind_param('s',$like); $stmt->execute(); $rows=$stmt->get_result();
echo '<form><input name="name" value="'.e($term).'"><button>Search</button></form><ul>';
while($p=$rows->fetch_assoc()) echo '<li>'.e($p['name']).' - '.e($p['price']).'</li>';
echo '</ul>';
