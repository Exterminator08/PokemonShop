<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

        <title>De WST supermarkt</title>
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">De WST supermarkt!</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav mr-auto">
                    <a class="nav-item nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link" href="categorie/groenten.php">Groenten</a>
                    <a class="nav-item nav-link" href="categorie/fruit.php">Fruit</a>
                    <a class="nav-item nav-link" href="categorie/brood.php">Brood</a>
                    <a class="nav-item nav-link" href="categorie/frisdrank.php">Frisdrank</a>
                </div>
                <a class="form-inline my-2 my-lg-0" href="#">
                    <i class="bi bi-basket-fill">Winkelmand</i>
                </a>
            </div>
    </nav>
  <div class="container">
  <h1>De WST supermarkt!</h1>
  <h2>Categorieën</h2>
  <p>Kies hieronder uit welke categorie u de producten wil zien:</p>
    <div class="row">
    
      <div class="col-sm-6 col-md-3">
      <div class="card">
        <i class="bi bi-cup-straw text-white bg-success card-img-top text-center" style="font-size: 55px;"></i>
        <div class="card-body">
        <h5 class="card-title">Groenten</h5>
        <p class="card-text">Het lekkerste in de categorie groenten</p>
        <a href="categorie/groenten.php" class="btn btn-success">Ga naar groenten</a>
        </div>
      </div>
      </div>
      
      <div class="col-sm-6 col-md-3">
      <div class="card">
        <i class="bi bi-cup-straw text-white bg-danger card-img-top text-center" style="font-size: 55px;"></i>
        <div class="card-body">
        <h5 class="card-title">Fruit</h5>
        <p class="card-text">Het lekkerste in de categorie fruit</p>
        <a href="categorie/fruit.php" class="btn btn-danger">Ga naar fruit</a>
        </div>
      </div>
      </div>
      
      <div class="col-sm-6 col-md-3">
      <div class="card">
        <i class="bi bi-cup-straw text-white bg-warning card-img-top text-center" style="font-size: 55px;"></i>
        <div class="card-body">
        <h5 class="card-title">Brood</h5>
        <p class="card-text">Het lekkerste in de categorie brood</p>
        <a href="categorie/brood.php" class="btn btn-warning">Ga naar brood</a>
        </div>
      </div>
      </div>
      
      <div class="col-sm-6 col-md-3">
      <div class="card">
        <i class="bi bi-cup-straw text-white bg-primary card-img-top text-center" style="font-size: 55px;"></i>
        <div class="card-body">
        <h5 class="card-title">Frisdrank</h5>
        <p class="card-text">Het lekkerste in de categorie frisdrank</p>
        <a href="categorie/frisdrank.php" class="btn btn-primary">Ga naar frisdrank</a>
        </div>
      </div>
      </div>
    </div>
  </div>
      <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

<?php

@include_once(__DIR__.'/src/Helpers/Auth.php');
@include_once(__DIR__.'/src/Helpers/Message.php');
@include_once(__DIR__.'/src/Database/Database.php');

setLastVisitedPage();

@include_once(__DIR__ . '/template/head.inc.php');

if (!Database::isConnected()) {
  die("Ошибка подключения: " . Database::getLastError());
}


// Get all the categories first
Database::query("SELECT * FROM `categories`");
$categories = Database::getAll();

// Now get all the products
Database::query("SELECT * FROM `products`");
$products = Database::getAll();

foreach ($products as $product) {
  echo "<a href='product.php?product_id={$product->id}'>{$product->name}</a><br>";
}
?>
      <?php if (hasMessage('success')): ?>
         <div class="uk-alert-success" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= getMessage('success') ?></p>
         </div>
      <?php endif; ?>

      <?php if (hasError('failed')) : ?>
         <div class="uk-alert-danger" uk-alert>
            <a href class="uk-alert-close" uk-close></a>
            <p><?= getError('failed') ?></p>
         </div>
      <?php endif; ?>

      <div class="uk-grid">
         <section class="uk-width-1-5">
            <h4>Categoriën</h4>
            <hr class="uk-divider" />
            <div>
               <input class="uk-checkbox" id="chickens" type="checkbox" name="chickens" />
               <label for="chickens">Wedstrijd kippen</label>
            </div>
            <div>
               <input class="uk-checkbox" id="paint" type="checkbox" name="paint" />
               <label for="paint">Verf</label>
            </div>
            <div>
               <input class="uk-checkbox" id="machines" type="checkbox" name="machines" />
               <label for="machines">Broedmachines</label>
            </div>
            <div>
               <input class="uk-checkbox" id="hokken" type="checkbox" name="hokken" />
               <label for="hokken">Hokken</label>
            </div>
         </section>
         <section class="uk-width-4-5">
            <h4 class="uk-text-muted uk-text-small">Gekozen categorieën: <span class="uk-text-small uk-text-primary">Alle</span></h4>
            <div class="uk-flex uk-flex-home uk-flex-wrap">
               <?php foreach ($products as $product) : ?>
                 <!-- PRODUCT KAART 1 -->
                 <a class="product-card uk-card uk-card-home uk-card-default uk-card-small uk-card-hover" href="product.php">
                     <div class="uk-card-media-top uk-align-center">
                        <img src="img/white-chicken.jpg" alt="Witte kip" class="product-image uk-align-center">
                     </div>
                     <div class="uk-card-body uk-card-body-home">
                        <p class="product-card-p">Een ideale kip voor beginnende wedstrijd deelnemer.</p>
                        <p class="product-card-p uk-text-large uk-text-bold uk-text-danger uk-text-right">&euro; 19.95</p>
                     </div>
                  </a>
                  <!-- EINDE PRODUCT KAART 1 -->
               <?php endforeach; ?>
            </div>
         </section>
      </div>

<?php
include_once(__DIR__ . '/template/foot.inc.php');
