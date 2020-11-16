<?php

session_start();

use PizzaPlaza\Components\Article;
use PizzaPlaza\Utilities\BackendSettings;
use PizzaPlaza\Utilities\DatabaseConnection;
use PizzaPlaza\Utilities\DatabaseConnectionSettings;
use PizzaPlaza\Utilities\InvalidConfigFileException;
use PizzaPlaza\Utilities\MissingConfigFileException;

define('APP_ROOT', realpath(dirname(__FILE__)));
require_once APP_ROOT . '/vendor/autoload.php';

function get_page_name($page)
{
    if (is_array($page)) {
        return $page['name'];
    }
    return $page;
}

// Load config
if (!file_exists(APP_ROOT . '/config.json')) {
    throw new MissingConfigFileException();
}

$configJson = file_get_contents(APP_ROOT . '/config.json');
if ($configJson === false) {
    throw new Exception("Could not load config file. Please check file permissions.");
}

$config = json_decode($configJson);
if ($jsonError = json_last_error()) {
    throw new InvalidConfigFileException();
}

// Create database connection
if (!isset($config->database)) {
    throw new InvalidConfigFileException("Missing node 'database' in config file.");
}
$connectionSettings = DatabaseConnectionSettings::fromStdClass($config->database);
try {
    $database = new DatabaseConnection($connectionSettings);
} catch (Exception $e) {
    exit("Could not connect to database. Error: " . $e->getMessage());
}

// Create database connection
if (!isset($config->backend)) {
    throw new InvalidConfigFileException("Missing node 'backend' in config file.");
}
$backendSettings = BackendSettings::fromStdClass($config->backend);

($setDiscount = function (DatabaseConnection $database) {
    $articles = array_keys(Article::fetchAll($database));
    $path = '/tmp/pizza_plaza_discount.json';
    if (!file_exists($path)) {
        $discountedArticleId = (int)$articles[random_int(0, count($articles) - 1)];
        file_put_contents($path, json_encode((object)[
            "date" => (new DateTime())->format('Y-m-d'),
            "id" => $discountedArticleId
        ]));
        define('DISCOUNTED_ARTICLE_ID', $discountedArticleId);
        Article::reset();
        return;
    }

    $discount = json_decode(file_get_contents($path));
    if ($discount->date !== (new DateTime())->format('Y-m-d')) {
        do {
            $newDiscountedArticleId = (int)$articles[random_int(0, count($articles) - 1)];
        } while ($newDiscountedArticleId === $discount->id && count($articles) > 1);
        file_put_contents($path, json_encode((object)[
            "date" => (new DateTime())->format('Y-m-d'),
            "id" => $newDiscountedArticleId
        ]));
        define('DISCOUNTED_ARTICLE_ID', $newDiscountedArticleId);
    } else {
        define('DISCOUNTED_ARTICLE_ID', $discount->id);
    }

    Article::reset();
})($database);

// Array of all available sites.
$availableSites = [
    "main" => "Startseite",
    "about" => "Über uns",
    "imprint" => "Impressum",
    "contact" => "Kontakt",
    "order" => "Online-Bestellung",
    "checkout" => [
        "name" => "Checkout",
        "parent" => "order"
    ],
    "checkout-complete" => [
        "name" => "Danke",
        "parent" => "order"
    ],
    "admin" => "Administration"
];

$endpoints = [
    "process-order"
];

// Default site value.
$currentSite = "main";
$currentSiteTitle = "";

$requestMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
$paramSite = filter_input(INPUT_GET, 'site', FILTER_SANITIZE_STRING);

// Load requested site-GET parameter
if (($requestMethod === "GET" || $requestMethod === "POST") && !empty($paramSite) && !empty($availableSites[$paramSite])) {
    $currentSite = $_GET['site'];
    $currentSiteTitle = is_array($availableSites[$currentSite]) ? $availableSites[$currentSite]['name'] : $availableSites[$currentSite];
} elseif (!empty($paramSite) && in_array($paramSite, $endpoints)) {
    // This can safely be done because endpoints must be whitelisted in the $endpoints array
    include APP_ROOT . '/endpoints/' . $paramSite . '.php';
}

// Use index file only for non-endpoint routes
if (empty($paramSite) || !in_array($paramSite, $endpoints)) {
    ob_start();
    include '../pages/index.php';
    ob_end_flush();
}