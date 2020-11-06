<?php

namespace PizzaPlaza\Components;

use PDO;
use PizzaPlaza\Utilities\DatabaseConnection;

class Article
{
    /**
     * @var static[]
     */
    protected static $articles = [];

    /**
     * @var int
     */
    public $ID;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var double
     */
    public $price;

    /**
     * @var string[]
     */
    public $extras = [];

    public function __construct(int $ID, string $name, float $price, $description = null)
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    public static function fetchAll(DatabaseConnection $connection)
    {
        if(!empty(self::$articles)) {
            return self::$articles;
        }

        $articles = [];
        $query = /** @lang MariaDB */
            <<<SQL
SELECT p.ID, p.name, p.description, p.price, e.name as extra
FROM Pizzas p
LEFT JOIN Pizza_has_Extra pe ON (pe.Pizzas_ID = p.ID)
LEFT JOIN Extras e ON (pe.Extras_ID = e.ID)
ORDER BY p.name ASC, extra ASC;
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
            if(empty($articles[$id])) {
                $articles[$id] = new static(
                    $row['ID'],
                    $row['name'],
                    $row['price'],
                    $row['description'] ?? null
                );
            }

            if(!empty($row['extra'])) {
                $articles[$id]->extras[] = $row['extra'];
            }
        }

        self::$articles = array_values($articles);
        return self::$articles;
    }
}