<?php


namespace PizzaPlaza\Components;


use PDO;
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
        if (!empty(self::$orders)) {
            return self::$orders;
        }

        $query = <<<SQL
SELECT `o`.`ID` as 'Order_ID', `o`.`timestamp`, `c`.`firstname`, `c`.`lastname`, `c`.`street`, 
       `c`.`streetnumber`, `c`.`zip`, `c`.`city`, `c`.`phone`, `i`.`ID` as 'OrderItems_ID', `i`.`quantity`,
       `i`.`Pizzas_ID` as 'Pizzas_ID', GROUP_CONCAT(`e`.`Extras_ID`) as 'Extras'
FROM `Order` `o`
JOIN `Customer` `c` ON (`o`.`Customer_ID` = `c`.`ID`)
JOIN `OrderItems` `i` ON (`i`.`Order_ID` = `o`.`ID`)
LEFT JOIN `OrderItem_has_Extra` `e` ON (`e`.`OrderItems_ID` = `i`.`ID`)
GROUP BY `i`.`ID`
ORDER BY `o`.`timestamp` ASC
SQL;

        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $orders = [];
        foreach ($result as $row) {
            $orderId = $row['Order_ID'];
            $orderItemId = $row['OrderItems_ID'];

            if (empty($orders[$orderId])) {
                $customer = new Customer(trim($row['firstname']), trim($row['lastname']));
                $customer->street = trim($row['street']);
                $customer->streetnumber = trim($row['streetnumber']);
                $customer->zip = trim($row['zip']);
                $customer->city = trim($row['city']);
                $customer->phone = trim($row['phone']);
                $orders[$orderId] = new self($customer, []);
                $orders[$orderId]->ID = $orderId;
                $orders[$orderId]->timestamp = $row['timestamp'];
            }

            if (!empty($articles[$row['Pizzas_ID']])) {
                $extrasMapped = array_filter(array_map(function ($extraId) use ($extras) {
                    return !empty($extras[$extraId]) ? $extras[$extraId] : null;
                }, array_filter(explode(',', $row['Extras']))));

                $orders[$orderId]->orderItems[$orderItemId] = new OrderItem(
                    $articles[$row['Pizzas_ID']],
                    $extrasMapped,
                    $row['quantity']
                );
            }
        }

        self::$orders = $orders;
        return self::$orders;
    }

    public static function deleteById(DatabaseConnection $db, int $orderId)
    {
        $query = <<<SQL
DELETE FROM `Customer` 
WHERE `Customer`.`ID` IN (
    SELECT `Order`.`customer_ID` 
    FROM `Order` 
    WHERE `Order`.`ID` = :orderId
);

DELETE FROM `OrderItem_has_Extra` 
WHERE `OrderItem_has_Extra`.`OrderItems_ID` IN (
    SELECT `OrderItems`.`ID` 
    FROM `OrderItems` 
    WHERE `OrderItems`.`Order_ID` = :orderId
);

DELETE FROM `OrderItems` 
WHERE `OrderItems`.`Order_ID` = :orderId;

DELETE FROM `Order` 
WHERE `Order`.`ID` = :orderId;
SQL;

        $stmt = $db->prepare($query);
        $stmt->execute([
            'orderId' => $orderId
        ]);
        self::$orders = [];
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
        self::$orders = []; // Force refresh on next fetch
    }
}