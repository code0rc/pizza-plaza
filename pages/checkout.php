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
    <h1 class="mb-5">Bestellung abschließen</h1>
  </div>
</div>
<div id="app">
  <div v-if="!initialized" class="d-flex align-content-center justify-content-center p-5">
    <div class="spinner-border text-secondary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
  <div v-cloak>
    <h5 v-if="order.length > 0">Möchten Sie noch letzte Änderungen an Ihrer Bestellung vornehmen?</h5>
    <order-summary :order="order" :delivery="delivery" v-on:clear_order="clearOrder()" v-on:delete="removeFromCart($event)"
                   v-on:set_quantity="setCartQuantity($event)"
                   v-on:update_extras="updateExtras($event)">
    </order-summary>

    <div class="row">
      <div class="col col-12 mb-5" v-if="order.length > 0">
        <a href="#" class="btn btn-danger float-right ml-2" v-on:click="clearOrder({redirect: '?site=order'})">Bestellvorgang
          abbrechen</a>
        <a class="btn btn-outline-primary float-left" href="?site=order">Zurück zur Produktauswahl</a>
      </div>
      <div class="col-12" v-else>
        <p>
          <strong>
            Es existiert aktuell keine Bestellung. Bitte starten Sie
            <a href="?site=order">hier</a> einen neuen Bestellvorgang!
          </strong>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-10 col-xl-8 offset-md-1 offset-xl-2">
        <form id="order_submit_form" class="mt-5 pt-5" v-if="order.length > 0" @submit.prevent="submitOrder($event.target)">
          <div class="form-row">
            <h5 class="col">Stimmt alles? Dann gib Deine Daten ein und schließe die Bestellung ab.</h5>
          </div>
          <div class="form-row">
            <div class="col-12 form-group">
              <label for="order_submit_form-first-name">Vorname</label>
              <input id="order_submit_form-first-name" type="text"
                     class="form-control" name="firstname" required
                     autocomplete="given-name">
            </div>
            <div class="col-12 form-group">
              <label for="order_submit_form-last-name">Nachname</label>
              <input id="order_submit_form-last-name" type="text"
                     class="form-control" name="lastname" required
                     autocomplete="family-name">
            </div>
          </div>
          <div class="form-row">
            <div class="col-8 form-group">
              <label for="order_submit_form-street">Straße <small class="text-muted" v-if="!delivery">(optional)</small></label>
              <input id="order_submit_form-street" type="text"
                     class="form-control" name="street"
                     autocomplete="shipping street-address address-line1" :required="delivery">
            </div>
            <div class="col-4 form-group">
              <label for="order_submit_form-streetnumber">Hausnummer <small
                    class="text-muted" v-if="!delivery">(optional)</small></label>
              <input id="order_submit_form-streetnumber" type="text"
                     class="form-control" name="streetnumber" :required="delivery">
            </div>
          </div>
          <div class="form-row">
            <div class="col-4 form-group">
              <label for="order_submit_form-zip">PLZ <small class="text-muted" v-if="!delivery">(optional)</small></label>
              <input id="order_submit_form-zip" type="tel"
                     class="form-control" name="zip"
                     autocomplete="shipping postal-code" :required="delivery">
            </div>
            <div class="col-8 form-group">
              <label for="order_submit_form-city">Stadt <small class="text-muted" v-if="!delivery">(optional)</small></label>
              <input id="order_submit_form-city" type="text"
                     class="form-control" name="city"
                     autocomplete="address-level2" :required="delivery">
            </div>
          </div>
          <div class="form-row">
            <div class="col-12 form-group">
              <label for="order_submit_form-phone">Telefonnummer <small class="text-muted">(optional für
                  Rückfragen)</small></label>
              <input id="order_submit_form-phone" type="tel"
                     class="form-control" name="phone"
                     autocomplete="shipping address-level2">
            </div>
          </div>
          <div class="form-row">
            <div class="col-12 form-group">
              <div class="form-check">
                <input id="order_submit_form-delivery" class="form-check-input" :disabled="!isEligibleForDelivery()"
                       name="accept-privacy-delivery" type="checkbox" v-model="delivery">
                <label class="form-check-label" for="order_submit_form-delivery">
                  Ich möchte den Lieferdienst in Anspruch nehmen (Mindestbestellwert 10&euro;, Gratis-Lieferung ab 25&euro;)
                </label>
              </div>
            </div>
            <div class="col-12 form-group">
              <div class="form-check">
                <input id="order_submit_form-accept" class="form-check-input"
                       name="accept-privacy-terms-conditions" type="checkbox" required>
                <label class="form-check-label" for="order_submit_form-accept">
                  Ich stimme zu, dass meine Daten zum Zwecke der Durchführung der Bestellung gespeichert und verarbeitet
                  werden
                  dürfen.
                </label>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="col-12">
              <button class="btn btn-primary float-right" type="submit">
                Jetzt verbindlich bestellen ({{ (getOrderTotal(order) + getDeliveryPrice()).toFixed(2) }}&euro;)
              </button>
            </div>
          </div>
          <div class="form-row">
            <div class="col-12">
              <p class="form-label text-muted">
                Mit (optional) markierte Felder sind freiwillige Eingaben.
              </p>
            </div>
          </div>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
<script src="/assets/js/vue-app.js"
        onload="initAppOrder(JSON.parse(atob('<?php echo $json ?>')), Vue)"></script>