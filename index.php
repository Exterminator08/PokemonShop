<?php
require_once __DIR__ . '/src/Database/Database.php';

if (!Database::isConnected()) {
    die("Database connection error: " . Database::getLastError());
}

if (!Database::query("SELECT * FROM products")) {
    die("Query error: " . Database::getLastError());
}

$products = Database::getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Product Catalog</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>All Products</h1>

<div class="product-grid">
   <?php foreach ($products as $product): ?>
      <div class="product-card">
         <?php if (!empty($product->image)): ?>
            <img src="<?= htmlspecialchars($product->image) ?>" alt="<?= htmlspecialchars($product->name) ?>" class="product-image">
         <?php endif; ?>
         <div class="product-title"><?= htmlspecialchars($product->name) ?></div>
         <div class="product-description"><?= htmlspecialchars($product->description) ?></div>
         <div class="product-price">€ <?= number_format($product->price, 2, ',', ' ') ?></div>
         <a href="product.php?product_id=<?= $product->id ?>" class="product-link">View Product</a>
      </div>
   <?php endforeach; ?>
</div>

</body>
</html>
