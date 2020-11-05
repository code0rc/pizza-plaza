<?php
require_once('./../components/Article.php');
$articles = Article::fetchAll();


?>
<div class="row">
    <div class="col-12">
        <h1>Wählen Sie Ihre Pizzen aus!</h1>
    </div>
</div>
<div class="row">
    <?php if (!empty($specialOffer = current($articles))) { ?>
        <div class="col col-12">
            <div class="card text-center my-3">
                <div class="card-header bg-dark text-light">
                    Angebot des Tages
                </div>
                <div class="card-body">
                    <h5 class="card-title">Pizza <?php echo htmlspecialchars($specialOffer->name) ?></h5>
                    <p class="card-text">
                        Statt
                        <del class="text-danger"><?php echo number_format($specialOffer->price, 2) ?> &euro;</del>
                        heute nur
                        <strong class="text-success"><?php echo number_format($specialOffer->price * 0.66, 2) ?> &euro;</strong>
                    </p>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php foreach ($articles as $article) { ?>
        <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
            <div class="card my-3 w-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Pizza <?php echo htmlspecialchars($article->name) ?>
                    <span class="badge badge-pill badge-success float-right"><?php echo htmlspecialchars($article->price) ?>
                        &euro;</span></h5>
                    <?php if ($article->description) { ?>
                        <h6 class="card-subtitle mb-2 text-muted" style="height: 3.2rem;"
                            aria-roledescription="price"><?php echo htmlspecialchars($article->description) ?></h6>
                    <?php } ?>
                </div>
                <div class="card-body">
                    <div class="float-right">
                        <a href="#" class="btn btn-sm btn-primary"><strong>+</strong></a>
                    </div>
                </div>
                <div class="card-footer">
                    <h6 class="card-subtitle my-2">Zutaten:</h6>
                    <p class="mb-2"
                       style="height: 3.2em;"><?php echo empty($article->extras) ? 'Keine' : htmlspecialchars(implode(', ', $article->extras)) ?></p>
                </div>
            </div>
        </div>
    <?php } ?>
</div>