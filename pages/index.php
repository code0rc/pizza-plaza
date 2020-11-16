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
  <script src="https://unpkg.com/vue@3.0.2/dist/vue.global.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <div class="container">
    <a class="navbar-brand" href="?site=main">Pizza Plaza</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?php if ($currentSite === 'main') echo 'active'; ?>">
          <a class="nav-link"
             href="?site=main"><?php echo htmlspecialchars(get_page_name($availableSites['main'])) ?></a>
        </li>
        <li class="nav-item <?php if ($currentSite === 'about') echo 'active'; ?>">
          <a class="nav-link"
             href="?site=about"><?php echo htmlspecialchars(get_page_name($availableSites['about'])) ?></a>
        </li>
        <li class="nav-item <?php if ($currentSite === 'order') echo 'active'; ?>">
          <a class="nav-link"
             href="?site=order"><?php echo htmlspecialchars(get_page_name($availableSites['order'])) ?></a>
        </li>
        <li class="nav-item <?php if ($currentSite === 'contact') echo 'active'; ?>">
          <a class="nav-link"
             href="?site=contact"><?php echo htmlspecialchars(get_page_name($availableSites['contact'])) ?></a>
        </li>
        <li class="nav-item <?php if ($currentSite === 'imprint') echo 'active'; ?>">
          <a class="nav-link"
             href="?site=imprint"><?php echo htmlspecialchars(get_page_name($availableSites['imprint'])) ?></a>
        </li>
        <li class="nav-item ml-md-3" v-cloak id="vue_order_summary_widget" v-if="itemsTotal > 0">
          <a class="nav-link" href="?site=checkout"><strong>&#x1f6d2; {{ itemsTotal }} Artikel ({{ priceTotal }}&euro;)</strong></a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php

$breadcrumb = [['link' => '?site=' . $currentSite, 'name' => $currentSiteTitle, 'active' => true]];
$parentSite = is_array($availableSites[$currentSite]) ?
    $availableSites[$currentSite]['parent'] :
    null;

while ($parentSite) {
    array_unshift(
        $breadcrumb,
        [
            'link' => '?site=' . $parentSite,
            'name' => get_page_name($availableSites[$parentSite]),
            'active' => false
        ]
    );

    $parentSite = is_array($availableSites[$parentSite]) ?
        $availableSites[$parentSite]['parent'] :
        null;
}

?>

<?php if ($currentSite !== "main") { ?>
  <div class="container mb-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Pizza Plaza</a></li>
          <?php
          foreach ($breadcrumb as $breadcrumbItem) {
              $class = $breadcrumbItem['active'] ? 'breadcrumb-item active' : 'breadcrumb-item';
              $aria = $breadcrumbItem['active'] ? 'aria-current="page"' : null;
              $name = htmlspecialchars($breadcrumbItem['name']);
              $content = !$breadcrumbItem['active'] ?
                  "<a href=\"{$breadcrumbItem['link']}\">$name</a>" :
                  $name;

              echo "<li class=\"$class\" $aria>$content</li>";

          }
          ?>
      </ol>
    </nav>
  </div>
<?php } ?>


<div class="container">
    <?php include $currentSite . '.php'; ?>
</div>

<script src="/dist/jquery/jquery.min.js"></script>
<script src="/dist/popper/umd/popper.min.js"></script>
<script src="/dist/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/main.js"></script>
<script>

  Vue.createApp({
    data: function () {
      return {
        itemsTotal: 0,
        priceTotal: 0
      }
    },
    methods: {
      loadData: function() {
        const data = JSON.parse(window.localStorage.getItem('pizza_plaza_order_summary'))
        this.priceTotal = data.priceTotal
        this.itemsTotal = data.itemsTotal
      }
    },
    created: function() {
      this.loadData()
      window.addEventListener('vue.order.updated', this.loadData)
    }
  }).mount('#vue_order_summary_widget')

</script>
</body>
</html>
