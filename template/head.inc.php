<?php
session_start();

@include_once(__DIR__ . '/../src/Helpers/Auth.php');
@include_once(__DIR__ . '/../src/Helpers/Message.php');
@include_once(__DIR__ . '/../src/Helpers/cart_stats.php');
@include_once(__DIR__ . '/../src/Database/Database.php');

$cartItems = [];
if (isLoggedIn() && Database::isConnected()) {
    Database::query(
        "SELECT id FROM cart WHERE customer_id = :id AND ordered = 0",
        [':id' => user_id()]
    );
    $cart = Database::get();
    if ($cart) {
        $cartId = $cart->id;
        Database::query(
            "SELECT p.name, p.price, p.image, ci.amount
             FROM cart_items ci
             JOIN products p ON p.id = ci.product_id
             WHERE ci.cart_id = :cart",
            [':cart' => $cartId]
        );
        $cartItems = Database::getAll();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>PokeShop</title>

   <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="../favicon.ico">
   <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
   <link rel="manifest" href="img/site.webmanifest">

   <link id="theme-style" rel="stylesheet" href="css/style.css">
   <link id="theme-style" rel="stylesheet" href="css/uikit.min.css">
</head>

<body>

   <nav class="uk-navbar-container">
      <div class="uk-container">
         <div uk-navbar>
            <div class="uk-navbar-left">
               <ul class="uk-navbar-nav">
                  <li>
                     <a href="/les1">Pokemon Shop</a>
                  </li>
               </ul>
            </div>

            <div class="uk-navbar-right">
               <ul class="uk-navbar-nav">
                  <li class="uk-active">
                     <a href="/les1"><span uk-icon="icon: home"></span>Home</a>
                  </li>

                  <?php if (guest()) : ?>
                     <li><a href="login.php"><span uk-icon="icon: sign-in"></span>Inloggen</a></li>
                     <li><a href="register.php"><span uk-icon="icon: file-edit"></span>Registreren</a></li>
                  <?php endif; ?>

                  <?php if (isLoggedIn()) : ?>
                     <li>
   <a href="cart.php">
      <span uk-icon="icon: cart"></span>
      Winkelwagen
      <span id="cart_amount_indicator" class="uk-badge"><?= countItemsInCart() ?></span>
   </a>
   <div class="uk-navbar-dropdown uk-dropdown-width-medium">
      <ul class="uk-nav uk-nav-default uk-padding-small">
         <?php if (empty($cartItems)): ?>
            <li class="uk-nav-header">Je winkelwagen is leeg</li>
         <?php else: ?>
            <li class="uk-nav-header">In uw winkelwagen:</li>
            <?php foreach ($cartItems as $item): ?>
               <li class="uk-flex uk-flex-middle uk-margin-small-bottom">
                  <div style="width: 40px; height: 40px; overflow: hidden; margin-right: 10px;">
                     <img src="<?= htmlspecialchars($item->image) ?>"
                          alt="<?= htmlspecialchars($item->name) ?>"
                          style="width: 100%; height: auto; object-fit: contain;">
                  </div>
                  <div style="flex: 1; font-size: 14px; line-height: 1.2;">
                     <?= htmlspecialchars($item->name) ?><br>
                     <small>x <?= intval($item->amount) ?> — € <?= number_format($item->price * $item->amount, 2, ',', ' ') ?></small>
                  </div>
               </li>
            <?php endforeach; ?>
            <li class="uk-nav-divider"></li>
            <li class="uk-text-center">
               <a href="cart.php" class="uk-button uk-button-primary uk-width-1-1">Bekijk winkelwagen</a>
            </li>
         <?php endif; ?>
      </ul>
   </div>
</li>


                     <li>
                        <a href="#"><span uk-icon="icon: user"></span>Welkom <?= htmlspecialchars(user()->firstname) ?> <span uk-navbar-parent-icon></span></a>
                        <div class="uk-navbar-dropdown">
                           <ul class="uk-nav uk-navbar-dropdown-nav">
                              <li class="uk-nav-header">Uw gegevens</li>
                              <li><a href="profile.php"><span uk-icon="icon: settings"></span>Profiel</a></li>
                              <li><a href="orderlist.php"><span uk-icon="icon: bag"></span>Bestellingen</a></li>
                              <li><a href="invoicelist.php"><span uk-icon="icon: credit-card"></span>Facturen</a></li>
                              <li><a href="returnlist.php"><span uk-icon="icon: refresh"></span>Retouren</a></li>
                              <li><a href="favorites.php"><span uk-icon="icon: heart"></span>Wensenlijst</a></li>

                              <li class="uk-nav-header">Contact</li>
                              <li><a href="customerservice.php"><span uk-icon="icon: info"></span>Klantenservice</a></li>

                              <li class="uk-nav-divider"></li>
                              <li>
                                 <form method="POST" action="logout.php" id="logout-form" style="display: none;">
                                    <input type="hidden" name="user_id" value="<?= user_id() ?>" />
                                 </form>

                                 <a href="javascript:void(0)" onclick="event.preventDefault(); if(confirm('Weet je zeker dat je wilt uitloggen?')) document.getElementById('logout-form').submit();">
                                    <span uk-icon="icon: sign-out"></span>Uitloggen
                                 </a>
                              </li>
                           </ul>
                        </div>
                     </li>
                  <?php endif; ?>

                  <li>
                     <a href="#">Thema <span uk-icon="icon: paint-bucket"></span></a>
                     <div class="uk-navbar-dropdown">
                        <ul class="uk-nav uk-navbar-dropdown-nav">
                           <li><a href="#" onclick="setTheme('style')">🌑 Donker Neon</a></li>
                           <li><a href="#" onclick="setTheme('style-white')">🌞 Licht</a></li>
                        </ul>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </nav>

   <main class="uk-container uk-padding">
