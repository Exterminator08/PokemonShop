<?php

@include_once(__DIR__ . '/src/Helpers/Auth.php');
@include_once(__DIR__ . '/src/Helpers/Message.php');
@include_once(__DIR__ . '/src/Database/Database.php');

@include_once(__DIR__ . '/template/head.inc.php');

if (guest()) {
    setMessage('login-messages', 'Log in om uw winkelwagen te bekijken.');
    header('Location: login.php');
    exit();
}

Database::query("
    SELECT 
      ci.id, ci.cart_id, ci.product_id, ci.amount,
      p.name, p.price, p.image,
      (ci.amount * p.price) AS product_total
    FROM cart_items ci
    JOIN products p ON p.id = ci.product_id
    JOIN cart c ON c.id = ci.cart_id
    WHERE c.customer_id = :uid
      AND c.ordered = 0
", [
    ':uid' => user_id()
]);
$cart_items     = Database::getAll();
$cart_total_amt = 0;
$cart_total_val = 0.0;
foreach ($cart_items as $ci) {
    $cart_total_amt += $ci->amount;
    $cart_total_val += $ci->product_total;
}
?>

<h1>Uw Winkelwagen</h1>

<?php if (empty($cart_items)): ?>
   <p>Uw winkelwagen is nog leeg.</p>
<?php else: ?>
   <table class="uk-table uk-table-divider">
      <thead>
         <tr>
            <th>Product</th><th>Aantal</th><th>Prijs</th><th>Subtotaal</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($cart_items as $i): ?>
         <tr>
            <td><?= htmlspecialchars($i->name) ?></td>
            <td class="uk-text-center"><?= $i->amount ?></td>
            <td class="uk-text-right">€ <?= number_format($i->price,2,',',' ') ?></td>
            <td class="uk-text-right">€ <?= number_format($i->product_total,2,',',' ') ?></td>
         </tr>
         <?php endforeach; ?>
      </tbody>
      <tfoot>
         <tr>
            <td colspan="3" class="uk-text-right">Totaal</td>
            <td class="uk-text-right">€ <?= number_format($cart_total_val,2,',',' ') ?></td>
         </tr>
      </tfoot>
   </table>

   <div class="uk-margin">
      <form method="POST" action="order_confirm.php">
         <button type="submit" class="uk-button uk-button-primary">Betalen</button>
      </form>
   </div>
<?php endif; ?>

<?php @include_once(__DIR__ . '/template/foot.inc.php'); ?>
