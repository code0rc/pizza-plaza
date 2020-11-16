<?php

session_start();

use PizzaPlaza\Utilities\BackendSettings;
use PizzaPlaza\Utilities\DatabaseConnection;
use PizzaPlaza\Utilities\DatabaseConnectionSettings;
use PizzaPlaza\Utilities\InvalidConfigFileException;
use PizzaPlaza\Utilities\MissingConfigFileException;

define('APP_ROOT', realpath(dirname(__FILE__)));
require_once APP_ROOT . '/vendor/autoload.php';

function get_page_name($page) {
    if(is_array($page)) {
        return $page['name'];
    }
    return $page;
}

// Load config
if(!file_exists(APP_ROOT . '/config.json')) {
    throw new MissingConfigFileException();
}

$configJson = file_get_contents(APP_ROOT . '/config.json');
if($configJson === false) {
    throw new Exception("Could not load config file. Please check file permissions.");
}

$config = json_decode($configJson);
if($jsonError = json_last_error()) {
    throw new InvalidConfigFileException();
}

// Create database connection
if(!isset($config->database)) {
    throw new InvalidConfigFileException("Missing node 'database' in config file.");
}
$connectionSettings = DatabaseConnectionSettings::fromStdClass($config->database);
try {
    $database = new DatabaseConnection($connectionSettings);
} catch(Exception $e) {
    exit("Could not connect to database. Error: " . $e->getMessage());
}

// Create database connection
if(!isset($config->backend)) {
    throw new InvalidConfigFileException("Missing node 'backend' in config file.");
}
$backendSettings = BackendSettings::fromStdClass($config->backend);

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
if(empty($paramSite) || !in_array($paramSite, $endpoints)) {
    ob_start();
    include '../pages/index.php';
    ob_end_flush();
}