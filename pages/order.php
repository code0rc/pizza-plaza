<?php

use PizzaPlaza\Components\Article;
use PizzaPlaza\Components\Extra;

$articles = Article::fetchAll($database);
$extras = Extra::fetchAll($database);
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
    <order-summary :order="order" v-on:clear_order="clearOrder()" v-on:delete="removeFromCart($event)" v-on:set_quantity="setCartQuantity($event)"></order-summary>
    <article-tile-list :articles="articles" v-slot:default="{ID, name, description, price, extras}">
      <article-tile :id="ID" :name="name"
                    :description="description"
                    :price="price" :extras="extras"
                    v-on:add_to_cart="addToCart($event)"/>
    </article-tile-list>
  </div>
</div>
<script src="https://unpkg.com/vue@3.0.2/dist/vue.global.js"></script>
<script src="/assets/js/vue-app.js"
        onload="initAppOrder(JSON.parse(atob('<?php echo $json ?>')), Vue)"></script>