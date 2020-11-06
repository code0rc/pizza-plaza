<?php


namespace PizzaPlaza\Utilities;


use Exception;

class InvalidDataTransferObjectException extends Exception
{
    protected $message = "Invalid data transfer object - make sure you're passing all required properties.";
}