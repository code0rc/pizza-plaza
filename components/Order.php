<?php


namespace PizzaPlaza\Components;


use PizzaPlaza\Utilities\DatabaseConnection;

class Order
{
    public $ID = 0;
    public $timestamp = null;
    public $customer = null;

    /**
     * @var static[]
     */
    protected static $orders = [];

    /**
     * @var OrderItem[]
     */
    public $orderItems = [];

    public function __construct(Customer $customer, array $orderItems)
    {
        $this->customer = $customer;
        $this->orderItems = $orderItems;
    }

    public function getPrice(): float
    {
        $price = 0;
        foreach ($this->orderItems as $orderItem) {
            $price += $orderItem->getPrice();
        }
        return (float)$price;
    }

    /**
     * @param DatabaseConnection $db
     * @param Article[] $articles
     * @param Extra[] $extras
     */
    public static function fetchAll(DatabaseConnection $db, array $articles, array $extras)
    {

    }

    /**
     * @param DatabaseConnection $db
     * @param Customer $customer
     * @return int Inserted customer ID
     */
    private static function insertCustomer(DatabaseConnection $db, Customer $customer): int
    {
        $query = <<<SQL
INSERT INTO `Customer` (`firstname`, `lastname`, `street`, `streetnumber`, `zip`, `city`, `phone`)
VALUES (:firstname, :lastname, :street, :streetnumber, :zip, :city, :phone)
SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([
            'firstname' => $customer->firstname,
            'lastname' => $customer->lastname,
            'street' => $customer->street,
            'streetnumber' => $customer->streetnumber,
            'zip' => $customer->zip,
            'city' => $customer->city,
            'phone' => $customer->phone
        ]);
        return $db->lastInsertId();
    }

    /**
     * @param DatabaseConnection $db
     * @param int $customerId
     * @return int Inserted order ID
     */
    private static function insertOrder(DatabaseConnection $db, int $customerId): int
    {
        $query = <<<SQL
INSERT INTO `Order` (`customer_ID`)
VALUES (:customer_ID);
SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([
            'customer_ID' => $customerId
        ]);
        return $db->lastInsertId();
    }

    /**
     * @param DatabaseConnection $db
     * @param OrderItem $orderItem
     * @param int $orderId
     */
    private static function insertOrderItem(DatabaseConnection $db, OrderItem $orderItem, int $orderId)
    {
        $query = <<<SQL
INSERT INTO `OrderItems` (`quantity`, `Order_ID`, `Pizzas_ID`)
VALUES (:quantity, :Order_ID, :Pizzas_ID);
SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([
            'quantity' => $orderItem->quantity,
            'Order_ID' => $orderId,
            'Pizzas_ID' => $orderItem->article->ID
        ]);
        $orderItemId = $db->lastInsertId();

        foreach ($orderItem->extras as $extra) {
            self::insertOrderItemExtra($db, $extra->ID, $orderItemId);
        }
    }

    /**
     * @param DatabaseConnection $db
     * @param int $extraId
     * @param int $orderItemId
     */
    private static function insertOrderItemExtra(DatabaseConnection $db, int $extraId, int $orderItemId)
    {
        $query = <<<SQL
INSERT INTO `OrderItem_has_Extra` (`OrderItems_ID`, `Extras_ID`)
VALUES (:OrderItems_ID, :Extras_ID);
SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([
            'OrderItems_ID' => $orderItemId,
            'Extras_ID' => $extraId,
        ]);
    }

    /**
     * @param DatabaseConnection $db
     * @param Order $order
     */
    public static function save(DatabaseConnection $db, self $order)
    {
        $customerId = self::insertCustomer($db, $order->customer);
        $orderId = self::insertOrder($db, $customerId);
        foreach ($order->orderItems as $orderItem) {
            self::insertOrderItem($db, $orderItem, $orderId);
        }
        self::$orders = [];
        //self::fetchAll($db, [], []);
    }
}