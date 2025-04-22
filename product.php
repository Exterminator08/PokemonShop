<?php
// product.php

@include_once(__DIR__ . '/src/Helpers/Auth.php');
@include_once(__DIR__ . '/src/Helpers/Message.php');
@include_once(__DIR__ . '/src/Database/Database.php');

@include_once(__DIR__ . '/template/head.inc.php');

if (!isset($_GET['product_id']) || !Database::isConnected()) {
   setError('general-error', 'Product niet gevonden of database niet bereikbaar.');
   echo '<div class="uk-alert-danger" uk-alert><p>' . getError('general-error') . '</p></div>';
   @include_once(__DIR__ . '/template/foot.inc.php');
   exit;
}

$productId = (int) $_GET['product_id'];
if (!Database::query("SELECT * FROM products WHERE id = :id", [':id' => $productId])) {
   setError('general-error', 'Query fout.');
   echo '<div class="uk-alert-danger" uk-alert><p>' . getError('general-error') . '</p></div>';
   @include_once(__DIR__ . '/template/foot.inc.php');
   exit;
}

$product = Database::get();
if (!$product) {
   setError('general-error', 'Product met dit ID niet gevonden.');
   echo '<div class="uk-alert-danger" uk-alert><p>' . getError('general-error') . '</p></div>';
   @include_once(__DIR__ . '/template/foot.inc.php');
   exit;
}
?>

<h1 class="uk-heading-divider"><?= htmlspecialchars($product->name) ?></h1>

<div class="uk-card uk-card-default uk-card-body uk-width-2-3@m uk-align-center">
   <?php if (!empty($product->image)): ?>
      <div class="uk-text-center uk-margin">
         <img
            src="<?= htmlspecialchars($product->image) ?>"
            alt="<?= htmlspecialchars($product->name) ?>"
            class="product-detail-image">
      </div>
   <?php endif; ?>
   <p><?= nl2br(htmlspecialchars($product->description)) ?></p>
   <p class="product-price uk-text-large uk-text-bold uk-text-danger">€ <?= number_format($product->price, 2, ',', ' ') ?></p>
   <?php if (isLoggedIn()): ?>
      <form method="POST" action="src/Formhandlers/addtocart.php">
         <input type="hidden" name="product_id" value="<?= $product->id ?>">
         <button class="uk-button uk-button-primary">In winkelwagen</button>
      </form>
   <?php else: ?>
      <a href="login.php" class="uk-button uk-button-primary">Log in om te bestellen</a>
   <?php endif; ?>
</div>

<?php @include_once(__DIR__ . '/template/foot.inc.php'); ?>