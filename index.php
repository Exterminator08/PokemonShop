<?php

function truncate(string $text, int $chars = 120): string
{
   if (mb_strlen($text) <= $chars) {
      return $text;
   }
   return mb_substr($text, 0, $chars) . '...';
}

@include_once(__DIR__ . '/src/Helpers/Message.php');
@include_once(__DIR__ . '/src/Helpers/Auth.php');
@include_once(__DIR__ . '/src/Database/Database.php');

@include_once(__DIR__ . '/template/head.inc.php');

if (!Database::isConnected()) {
   die('<div class="uk-alert-danger" uk-alert><p>Database connection error: ' . Database::getLastError() . '</p></div>');
}

Database::query("SELECT * FROM categories");
$categories = Database::getAll();

$categoryId = $_GET['category'] ?? '';

if ($categoryId) {
   Database::query("SELECT * FROM products WHERE category_id = :cat", [':cat' => $categoryId]);
} else {
   Database::query("SELECT * FROM products");
}
$products = Database::getAll();
?>

<?php if (hasMessage('login-messages')): ?>
   <div class="uk-alert-primary" uk-alert>
      <a class="uk-alert-close" uk-close></a>
      <p><?= getMessage('login-messages') ?></p>
   </div>
<?php endif; ?>

<form method="GET" class="uk-margin-bottom">
   <div class="uk-flex uk-flex-left uk-flex-middle uk-grid-small" uk-grid>
      <div>
         <select name="category" class="uk-select">
            <option value="">Alle categorieën</option>
            <?php foreach ($categories as $cat): ?>
               <option value="<?= $cat->id ?>" <?= ($categoryId == $cat->id ? 'selected' : '') ?>>
                  <?= htmlspecialchars($cat->name) ?>
               </option>
            <?php endforeach; ?>
         </select>
      </div>
      <div>
         <button class="uk-button uk-button-primary">Filter</button>
      </div>
   </div>
</form>

<h1 class="uk-heading-divider">Onze Producten</h1>

<div class="uk-grid-small uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l" uk-grid>
   <?php foreach ($products as $product): ?>
      <div>
         <div class="uk-card uk-card-default uk-card-hover uk-card-small clickable-card"
            onclick="window.location.href='product.php?product_id=<?= $product->id ?>';"
            style="cursor: pointer;">
            <?php if (!empty($product->image)): ?>
               <div class="uk-card-media-top">
                  <img
                     src="<?= htmlspecialchars($product->image) ?>"
                     alt="<?= htmlspecialchars($product->name) ?>"
                     class="product-image">
               </div>
            <?php endif; ?>
            <div class="uk-card-body">
               <h3 class="uk-card-title"><?= htmlspecialchars($product->name) ?></h3>
               <p><?= htmlspecialchars(truncate($product->description, 120)) ?></p>
               <p class="product-price">€ <?= number_format($product->price, 2, ',', ' ') ?></p>
               <a href="product.php?product_id=<?= $product->id ?>"
                  class="uk-button uk-button-primary uk-width-1-1"
                  onclick="event.stopPropagation();">
                  Bekijk
               </a>
            </div>
         </div>
      </div>
   <?php endforeach; ?>
</div>

<?php @include_once(__DIR__ . '/template/foot.inc.php'); ?>