<div class="container">
  <div class="row">
    <div class="col-12 col-lg-10 offset-lg-1 my-5 py-5">
        <h1 class="text-center">Deine Bestellung wird bearbeitet!</h1>
        <p class="text-center text-muted mt-5">
            <strong>
                Lieber Kunde, vielen Dank für Deine Bestellung, die wir soeben erhalten haben. Wir beginnen in Kürze mit der Bearbeitung.
                <?php if($price = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)) { ?>
                    Bitte halte für den Lieferanten den Rechnungsbetrag von <span class="badge-success badge-pill">
                        <?php echo number_format($price / 100, 2) ?> &euro;</span> bereit.
                <?php } ?>
            </strong>
        </p>
        <p class="text-center mt-5"><a href="/" class="btn btn-lg btn-outline-primary">Zur Startseite</a></p>
    </div>
  </div>
</div>