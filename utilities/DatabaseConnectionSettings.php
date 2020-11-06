<?php


namespace PizzaPlaza\Utilities;


class DatabaseConnectionSettings extends DataTransferObject
{
    public $database = null;
    public $hostname = "localhost";
    public $port = "3306";
    public $username = "root";
    public $password = "";
    public $charset = "utf8mb4";
}