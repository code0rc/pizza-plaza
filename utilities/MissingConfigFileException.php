<?php


namespace PizzaPlaza\Utilities;


use Exception;

class MissingConfigFileException extends Exception
{
    protected $message =
        "Missing config file 'config.json' in application root directory. " .
        "Please consult the project documentation.";
}