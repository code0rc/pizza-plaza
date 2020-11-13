<?php

namespace PizzaPlaza\Components;

use Exception;
use PDO;
use PizzaPlaza\Utilities\DatabaseConnection;

class Extra
{
    /**
     * @var static[]
     */
    protected static $extras = [];

    /**
     * @var int
     */
    public $ID;

    /**
     * @var string
     */
    public $name;

    /**
     * @var double
     */
    public $price;

    public function __construct(int $ID, string $name, float $price)
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->price = $price;
    }

    public static function fetchAll(DatabaseConnection $connection)
    {
        if(!empty(self::$extras)) {
            return self::$extras;
        }

        $extras = [];
        $query = /** @lang MariaDB */
            <<<SQL
SELECT e.ID, e.name, e.price
FROM Extras e
WHERE e.isChoosable > 0
ORDER BY e.name ASC;
SQL;

        try {
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            exit("Could not fetch data from database.");
        }

        foreach ($rows as $row) {
            $id = $row['ID'];
            $extras[$id] = new static(
                $row['ID'],
                $row['name'],
                $row['price']
            );
        }

        self::$extras = array_values($extras);
        return self::$extras;
    }
}