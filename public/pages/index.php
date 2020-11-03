<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pizza Plaza</title>
    <link rel="stylesheet" href="/dist/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="?site=main">Pizza Plaza</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php if($currentSite === 'main') echo 'active'; ?>">
                <a class="nav-link" href="?site=main">Startseite</a>
            </li>
            <li class="nav-item <?php if($currentSite === 'about') echo 'active'; ?>">
                <a class="nav-link" href="?site=about">&Uuml;ber uns</a>
            </li>
            <li class="nav-item <?php if($currentSite === 'contact') echo 'active'; ?>">
                <a class="nav-link" href="?site=contact">Kontakt</a>
            </li>
            <li class="nav-item <?php if($currentSite === 'imprint') echo 'active'; ?>">
                <a class="nav-link" href="?site=imprint">Impressum</a>
            </li>

        </ul>
    </div>
</nav>

<div class="container">
    <?php include $currentSite . '.php'; ?>
</div>

<script src="/dist/jquery/jquery.min.js"></script>
<script src="/dist/popper/umd/popper.min.js"></script>
<script src="/dist/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/main.js"></script>
</body>
</html>
