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
    <?php foreach ($articles as $article) { ?>
        <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
            <div class="card my-3 w-100">
                <div class="card-body">
                    <h5 class="card-title">Pizza <?php echo htmlspecialchars($article->name) ?></h5>
                    <h6 class="badge badge-pill badge-success mb-3"><?php echo htmlspecialchars($article->price) ?>
                        &euro;</h6>
                    <?php if ($article->description) { ?>
                        <h6 class="card-subtitle mb-2 text-muted" style="height: 5rem;"
                            aria-roledescription="price"><?php echo htmlspecialchars($article->description) ?></h6>
                    <?php } ?>
                    <a href="#" class="btn btn-md btn-primary float-right mt-5">Auswählen</a>
                </div>
                <div class="card-footer">
                    <h6 class="card-subtitle my-2">Zutaten:</h6>
                        <p class="mb-2"
                           style="height: 4em;"><?php echo empty($article->extras) ? 'Keine' : htmlspecialchars(implode(', ', $article->extras)) ?></p>
                </div>
            </div>
        </div>
    <?php } ?>
</div>