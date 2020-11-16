<?php

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$loginFailed = false;

$logoutAction = filter_input(INPUT_GET, 'logout', FILTER_SANITIZE_NUMBER_INT);

if ($requestMethod === 'POST') {
    if ($username === $backendSettings->username && $password === $backendSettings->password) {
        $_SESSION['isLoggedIn'] = true;
        header('Location: ?site=admin');
    } else {
        $loginFailed = true;
    }
}

?>
<div class="container">
  <div class="row">
    <div class="col-12 col-lg-8 offset-lg-2">
      <?php if(!empty($logoutAction)) { ?>
          <div class="alert alert-success mb-3">
            <span class="alert-heading">Sie wurden erfolgreich abgemeldet!</span>
            <a href="?site=admin" class="float-right">&#x2715;</a>
          </div>
      <?php } ?>
      <h1 class="mb-3">Bestellungen verwalten</h1>
      <p>Sie müssen sich anmelden um auf diesen Bereich zugreifen zu können.</p>
      <div class="alert alert-secondary mt-5">
        <h4 class="mb-4">Anmelden</h4>
        <form action="" method="post">
          <div class="form-row">
            <div class="form-group col-12">
              <label for="input_username" class="label">Benutzername</label>
              <input type="text" class="form-control" id="input_username" name="username" autocomplete="off" required
                     value="<?php echo $username; ?>" autofocus>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-12">
              <label for="input_password" class="label">Passwort</label>
              <input type="password" class="form-control" id="input_password" name="password" autocomplete="off"
                     required>
            </div>
          </div>
          <input type="hidden" name="login" value="1">
          <div class="form-row">
            <div class="form-group col-12">
                <?php if ($loginFailed) { ?>
                  <p class="text-danger">Benutzername oder Passwort falsch!</p>
                <?php } ?>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-12 mt-5">
              <button type="submit" class="btn btn-primary float-right">Anmelden</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>