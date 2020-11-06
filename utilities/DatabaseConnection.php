<?php


namespace PizzaPlaza\Utilities;


use PDO;

class DatabaseConnection extends PDO
{
    public function __construct(DatabaseConnectionSettings $connectionSettings)
    {
        parent::__construct(
            "mysql:dbname={$connectionSettings->database};" .
            "host={$connectionSettings->hostname}:{$connectionSettings->port};" .
            "charset={$connectionSettings->charset}",
            $connectionSettings->username,
            $connectionSettings->password

        );
    }
}