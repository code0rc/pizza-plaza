<?php

use PizzaPlaza\Components\Article;
use PizzaPlaza\Components\Extra;

$articles = array_values(Article::fetchAll($database));
$extras = array_values(Extra::fetchAll($database));
$data = (object)[
    'payload' => (object)[
        'articles' => $articles,
        'extras' => $extras
    ]
];
$json = base64_encode(json_encode($data));
?>
<div class="row">
  <div class="col-12">
    <h1>Wählen Sie Ihre Pizzen aus!</h1>
  </div>
</div>
<div id="app">
  <div v-if="!initialized" class="d-flex align-content-center justify-content-center p-5">
    <div class="spinner-border text-secondary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
  <div v-cloak>
    <order-summary :order="order" v-on:clear_order="clearOrder()" v-on:delete="removeFromCart($event)"
                   v-on:set_quantity="setCartQuantity($event)"
                   v-on:update_extras="updateExtras($event)"></order-summary>

    <div class="row">
      <div class="col col-12 mb-5" v-if="order.length > 0">
        <a class="btn btn-primary float-right ml-2" href="?site=checkout">Weiter zum Checkout</a>
        <a href="#" class="btn btn-outline-danger float-right" v-on:click="clearOrder()">Bestellvorgang
          abbrechen</a>
      </div>
    </div>

    <article-tile-list :articles="articles" v-slot:default="{ID, name, description, price, extras, fullPrice}">
      <article-tile :id="ID" :name="name"
                    :description="description"
                    :price="price" :extras="extras"
                    :full-price="fullPrice"
                    v-on:add_to_cart="addToCart($event)"/>
    </article-tile-list>
  </div>
</div>
<script src="/assets/js/vue-app.js"
        onload="initAppOrder(JSON.parse(atob('<?php echo $json ?>')), Vue)"></script>