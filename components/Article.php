<?php

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

    public static function fetchAll()
    {
        if(!empty(self::$articles)) {
            return self::$articles;
        }

        $query = /** @lang MariaDB */
            <<<SQL
SELECT p.ID, p.name, p.description, p.price, e.name as extra
FROM Pizzas p
LEFT JOIN Pizza_has_Extra pe ON (pe.Pizzas_ID = p.ID)
LEFT JOIN Extras e ON (pe.Extras_ID = e.ID)
ORDER BY p.name ASC, extra ASC;
SQL;

        // error handling code intentionally omitted for reasons of time
        $dbConnection = new PDO('mysql:dbname=pizza_plaza;host=database:3306;charset=utf8mb4', 'root', 'root');
        $stmt = $dbConnection->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $id = $row['ID'];
            if(empty(self::$articles[$id])) {
                self::$articles[$id] = new static(
                    $row['ID'],
                    $row['name'],
                    $row['price'],
                    $row['description'] ?? null
                );
            }

            if(!empty($row['extra'])) {
                self::$articles[$id]->extras[] = $row['extra'];
            }
        }

        return self::$articles;
    }
}