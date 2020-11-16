<?php


namespace PizzaPlaza\Components;


use Exception;
use PizzaPlaza\Utilities\DatabaseConnection as DB;
use stdClass;

class OrderItem
{
    public $article = null;
    public $quantity = 1;
    public $extras = [];

    /**
     * OrderItem constructor.
     * @param Article $article
     * @param Extra[] $extras
     * @param int $quantity
     * @throws Exception
     */
    public function __construct(
        Article $article,
        array $extras,
        int $quantity
    )
    {
        $this->article = $article;
        $this->extras = $extras;

        if (isset($quantity)) {
            if ($quantity > 20) {
                $this->quantity = 20;
            } elseif ($quantity < 1) {
                $this->quantity = 1;
            } else {
                $this->quantity = $quantity;
            }
        }
    }

    public function getPrice(): float
    {
        $price = $this->article->price * $this->quantity;
        foreach ($this->extras as $extra) {
            $price += $extra->price * $this->quantity;
        }
        return (float)$price;
    }
}