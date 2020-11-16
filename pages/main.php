<?php

use PizzaPlaza\Components\Article;

$articles = Article::fetchAll($database);
$discountedArticle = $articles[DISCOUNTED_ARTICLE_ID];

?>

<div class="jumbotron text-center alert-ligh">
  <p class="h5">Angebot des Tages</p>
  <p class="h1 mb-3">Pizza <?php echo htmlspecialchars($discountedArticle->name) ?></p>
  <p class="h4 mb-5">
    Heute statt <span class="badge-pill badge-danger">
      <del><?php echo htmlspecialchars(number_format($discountedArticle->fullPrice, 2)) ?>&nbsp;&euro;</del>
    </span>&nbsp;nur&nbsp;<span class="badge-pill badge-success"><?php echo htmlspecialchars(number_format($discountedArticle->price, 2)) ?>&nbsp;&euro;</span></p>
  <p><a href="?site=order" class="btn btn-lg btn-primary">Jetzt Bestellen & Sparen</a></p>
</div>

<p class="lead">Herzlich Willkommen auf der Webseite von Pizza Plaza!</p>
<p>Wir sind ein fiktives Restaurant in Gießen/Langgöns. Auf unserer Webseite erfahren Sie nähere Informationen über uns,
  Wege, uns zu kontaktieren und erhalten bald die Möglichkeit, Bestellungen online durchzuführen.</p>
<p>Ihr Restaurant Pizza Plaza</p>