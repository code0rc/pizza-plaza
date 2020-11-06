<?php

use PizzaPlaza\Utilities\DatabaseConnection;
use PizzaPlaza\Utilities\DatabaseConnectionSettings;
use PizzaPlaza\Utilities\InvalidConfigFileException;
use PizzaPlaza\Utilities\MissingConfigFileException;

define('APP_ROOT', realpath(dirname(__FILE__)));
require_once APP_ROOT . '/vendor/autoload.php';

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


// Array of all available sites.
$availableSites = [
    "main" => "Startseite",
    "about" => "Über uns",
    "imprint" => "Impressum",
    "contact" => "Kontakt",
    "order" => "Online-Bestellung"
];

// Default site value.
$currentSite = "main";
$currentSiteTitle = "";

$requestMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
$paramSite = filter_input(INPUT_GET, 'site', FILTER_SANITIZE_STRING);

// Load requested site-GET parameter
if ($requestMethod === "GET" && !empty($paramSite) && !empty($availableSites[$paramSite])) {
    $currentSite = $_GET['site'];
    $currentSiteTitle = $availableSites[$currentSite];
}

//Load the template.
include '../pages/index.php';
