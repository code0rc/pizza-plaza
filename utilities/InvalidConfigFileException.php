<?php


namespace PizzaPlaza\Utilities;


use Exception;

class InvalidConfigFileException extends Exception
{
    protected $message = "The config file must only contain valid JSON formatted data.";
}