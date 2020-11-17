<?php

use PizzaPlaza\Components\Article;
use PizzaPlaza\Components\Extra;
use PizzaPlaza\Components\Order;

if (!empty($deleteOrderId = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT))) {
    Order::deleteById($database, $deleteOrderId);
    header('Location: ?site=admin');
}

$articles = Article::fetchAll($database);
$extras = Extra::fetchAll($database);
$orders = Order::fetchAll($database, $articles, $extras);

?>
<div class="row">
  <div class="col-12">
    <a href="?site=admin&logout=1" class="btn btn-outline-danger">Abmelden</a>
  </div>
</div>
<div class="row">
  <div class="col-12 mt-5 mb-4">
    <h1>Bestellungen verwalten</h1>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <h5>Alle Bestellungen</h5>
  </div>

    <?php if (empty($orders)) { ?>
      <div class="col-12">
        <div class="alert alert-info">Keine Bestellungen vorhanden</div>
      </div>
    <?php } ?>

    <?php foreach ($orders as $order) { ?>
      <div class="col-12">
        <div class="card w-100 mb-3">
          <div class="card-header d-flex justify-content-between">
            <h5 class="card-title mb-0"># Order-No. <?php echo htmlspecialchars($order->ID) ?></h5>
            <div class="card-title h5 mb-0">
                <?php echo htmlspecialchars((new DateTime($order->timestamp))->format('d.m.Y H:i')); ?>
            </div>
          </div>
          <div class="card-body text-muted">
            <h6>Kundendaten</h6>
              <?php echo htmlspecialchars($order->customer->firstname) ?>
              <?php echo htmlspecialchars($order->customer->lastname) ?><br>
              <?php if (!empty($order->customer->street)) { ?>
                  <?php echo htmlspecialchars($order->customer->street) ?>
                  <?php echo htmlspecialchars($order->customer->streetnumber) ?><br>
              <?php } ?>
              <?php if (!empty($order->customer->zip) || !empty($order->customer->city)) { ?>
                  <?php echo htmlspecialchars($order->customer->zip) ?>
                  <?php echo htmlspecialchars($order->customer->city) ?>
                <br>
              <?php } ?>
              <?php if (!empty($order->customer->phone)) { ?>
                Tel.: <?php echo htmlspecialchars($order->customer->phone) ?>
              <?php } ?>
          </div>
          <ul class="list-group list-group-flush">
              <?php foreach ($order->orderItems as $item) { ?>
                <li class="list-group-item bg-light text-dark"><h5>
                    <strong><?php echo htmlspecialchars($item->quantity) ?></strong>&nbsp;&times;
                    Pizza <?php echo htmlspecialchars($item->article->name) ?><?php if ($item->article->discounted) {
                            echo " <span class='text-danger'>(Rabatt -33%)</span>";
                        } ?><br>
                        <?php if (count($item->extras) > 0) { ?>
                          Extras: <span class="text-muted">
                            <?php
                            echo htmlspecialchars(implode(', ', array_map(function ($extra) {
                                return $extra->name;
                            }, $item->extras)))
                            ?>
                      </span>
                        <?php } else { ?>
                          <!--Extras: <span class="text-muted">keine</span>-->
                        <?php } ?>
                  </h5>
                </li>
              <?php } ?>
          </ul>
          <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="h4 badge-pill badge-success py-2">
                <?php echo htmlspecialchars(number_format($order->getPrice(), 2)) ?> &euro;
            </span>
              <?php if ($order->delivery && $order->getPrice() < 25) { ?>
                <span class="h4"><span class="badge-pill badge-warning py-2">
                  inkl. 1.50 &euro; Lieferpauschale
                </span></span>
              <?php } elseif ($order->delivery && $order->getPrice() >= 25) { ?>
                <span><span class="h4 badge-pill badge-info py-2">
                  inkl. GRATIS Lieferservice
                </span></span>
              <?php } ?>
            <a href="?site=admin&delete=<?php echo htmlspecialchars($order->ID) ?>"
               class="btn btn-lg btn-danger py-3 px-4"><strong>Bestellung Abschließen</strong></a>
          </div>
        </div>
      </div>
    <?php } ?>

  <script>
    window.setInterval(function () { window.location.reload() }, 20000)
  </script>
</div>
