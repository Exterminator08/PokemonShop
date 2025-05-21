<?php

@include_once(__DIR__ . '/src/Helpers/Auth.php');
@include_once(__DIR__ . '/src/Helpers/Message.php');
@include_once(__DIR__ . '/src/Database/Database.php');

@include_once(__DIR__ . '/template/head.inc.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || guest()) {
    setError('order-error', 'U moet ingelogd zijn om af te rekenen.');
    header('Location: login.php');
    exit();
}

Database::query(
    "SELECT id FROM cart WHERE customer_id = :uid AND ordered = 0",
    [':uid' => user_id()]
);
$cart = Database::get();
if (!$cart) {
    setError('order-error', 'Uw winkelwagen is leeg.');
    header('Location: order.php');
    exit();
}
$cartId = $cart->id;

Database::query(
    "SELECT ci.product_id, ci.amount, p.name, p.price, p.image,
            (ci.amount * p.price) AS product_total
     FROM cart_items ci
     JOIN products p ON p.id = ci.product_id
     WHERE ci.cart_id = :cid",
    [':cid' => $cartId]
);
$cart_items = Database::getAll();

Database::query(
    "UPDATE cart
     SET ordered    = 1,
         updated_at = :now
     WHERE id = :cid",
    [
        ':now' => date('Y-m-d H:i:s'),
        ':cid' => $cartId
    ]
);

Database::query(
    "INSERT INTO orders (customer_id, order_date)
     VALUES (:uid, :odate)",
    [
        ':uid'   => user_id(),
        ':odate' => date('Y-m-d H:i:s')
    ]
);
$orderId = Database::lastInserted();
foreach ($cart_items as $item) {
    Database::query(
        "INSERT INTO order_items (order_id, product_id, amount)
         VALUES (:oid, :pid, :amt)",
        [
            ':oid' => $orderId,
            ':pid' => $item->product_id,
            ':amt' => $item->amount
        ]
    );
}

Database::query(
    "DELETE FROM cart_items WHERE cart_id = :cid",
    [':cid' => $cartId]
);

setMessage('order-success', 'Bedankt voor uw betaling! Uw winkelwagen is nu geleegd.');
?>

<div class="uk-grid-small uk-margin-top" uk-grid>
  <div class="uk-width-1-1">
    <div class="uk-card uk-card-default uk-card-body uk-text-center">
      <h1>Bedankt voor uw bestelling</h1>
      <p>Uw ordernummer is: <strong id="orderNumber">0000000</strong></p>
      <p>U ontvangt binnen enkele ogenblikken een bevestiging per e-mail.</p>
      <a href="index.php" class="uk-button uk-button-default uk-margin-top">Terug naar Home</a>
    </div>
  </div>

  <div class="uk-width-1-1">
    <div class="uk-card uk-card-default uk-card-body">
      <h2>Overzicht van uw bestelling</h2>
      <table class="uk-table uk-table-divider">
        <thead>
          <tr>
            <th>Product</th>
            <th>Aantal</th>
            <th>Prijs</th>
            <th>Subtotaal</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($cart_items as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item->name) ?></td>
            <img class="uk-order-confirm-img" src="<?= $cart_item->image ?>" alt="" />

            <td class="uk-text-center"><?= $item->amount ?></td>
            <td class="uk-text-right">€ <?= number_format($item->price,2,',',' ') ?></td>
            <td class="uk-text-right">€ <?= number_format($item->product_total,2,',',' ') ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="uk-text-right uk-text-bold">Totaal</td>
            <td class="uk-text-right uk-text-bold">
              € <?= number_format(array_reduce($cart_items, fn($sum,$i)=>$sum+$i->product_total,0),2,',',' ') ?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<script>
  window.onload = () => {
    const num = Math.floor(Math.random() * 1e7).toString().padStart(7, '0');
    document.getElementById('orderNumber').textContent = num;
  };
</script>

<?php @include_once(__DIR__ . '/template/foot.inc.php'); ?>
