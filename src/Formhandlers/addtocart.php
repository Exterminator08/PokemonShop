<?php
session_start();
date_default_timezone_set('Europe/Amsterdam');

@include_once(__DIR__ . '/../Helpers/Auth.php');
@include_once(__DIR__ . '/../Helpers/Message.php');
@include_once(__DIR__ . '/../Database/Database.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isLoggedIn()) {
   setError('addtocart-error', 'Log a.u.b. in om een bestelling te kunnen doen, nog geen account registreer dan eerst a.u.b.');
   header('Location: login.php');
   exit();
}

if (!isset($_POST['product_id'])) {
   setError('no-product-error', 'ID van het product ontbreekt');
   $redirect = $_POST['return_url'] ?? 'index.php';
   header('Location: ' . $redirect);
   exit();
}

$customerId = user_id();
$productId  = intval($_POST['product_id']);
$returnUrl  = $_POST['return_url'] ?? 'index.php';

Database::query(
   "SELECT id FROM cart WHERE customer_id = :cust AND ordered = 0",
   [':cust' => $customerId]
);
$cart = Database::get();

if (!$cart) {
   Database::query(
      "INSERT INTO cart(customer_id) VALUES(:cust)",
      [':cust' => $customerId]
   );
   $cartId = Database::lastInserted();
} else {
   $cartId = $cart->id;
}

Database::query(
   "SELECT * FROM cart_items WHERE cart_id = :cart AND product_id = :prod",
   [':cart' => $cartId, ':prod' => $productId]
);
$cartItem = Database::get();

if (!$cartItem) {
   Database::query(
      "INSERT INTO cart_items(cart_id, product_id, amount)
       VALUES(:cart, :prod, 1)",
      [':cart' => $cartId, ':prod' => $productId]
   );
   setMessage('product-added', "Product is toegevoegd aan de winkelwagen");
} else {
   Database::query(
      "UPDATE cart_items SET amount = :amt
       WHERE cart_id = :cart AND product_id = :prod",
      [':amt' => $cartItem->amount + 1, ':cart' => $cartId, ':prod' => $productId]
   );
   setMessage('product-amount-increased', "Aantal van dit product in de winkelwagen verhoogd met 1");
}

header('Location: ' . $returnUrl);
exit();
