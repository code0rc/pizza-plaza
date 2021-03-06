<?php

namespace PizzaPlaza\Components;

use Exception;
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
     * @var double|null
     */
    public $fullPrice = null;

    /**
     * @var bool
     */
    public $discounted = false;

    /**
     * @var string[]
     */
    public $extras = [];

    public function __construct(int $ID, string $name, float $price, $description = null, $discountPrice = null)
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    /**
     * @param DatabaseConnection $connection
     * @return array|static
     */
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

                if(defined('DISCOUNTED_ARTICLE_ID') && (string)DISCOUNTED_ARTICLE_ID === (string)$id) {
                    $articles[$id]->fullPrice = $articles[$id]->price;
                    $articles[$id]->price = round($articles[$id]->price * 0.66, 2);
                    $articles[$id]->discounted = true;
                }
            }

            if(!empty($row['extra'])) {
                $articles[$id]->extras[] = $row['extra'];
            }
        }

        self::$articles = $articles;
        return self::$articles;
    }

    public static function reset()
    {
        self::$articles = [];
    }
}