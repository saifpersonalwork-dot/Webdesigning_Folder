<?php require dirname(__DIR__) . '/partials/header.php'; ?>
<h2>Products</h2>
<table border="1" cellpadding="6">
  <tr><th>ID</th><th>Name</th><th>Price</th></tr>
  <?php foreach ($products as $product): ?>
    <tr>
      <td><?= (int)$product['id'] ?></td>
      <td><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></td>
      <td><?= htmlspecialchars((string)$product['price'], ENT_QUOTES, 'UTF-8') ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<?php require dirname(__DIR__) . '/partials/footer.php'; ?>
