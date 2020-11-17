<?php

use PizzaPlaza\Components\Article;
use PizzaPlaza\Components\Customer;
use PizzaPlaza\Components\Extra;
use PizzaPlaza\Components\Order;
use PizzaPlaza\Components\OrderItem;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 400 Bad Request', false, 400);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'));
    $customer = new Customer($data->customer->firstname, $data->customer->lastname);

    if (empty($data->customer->acceptPrivacyTermsConditions)) {
        throw new Exception("You need to accept the privacy terms and conditions!");
    }

    if (($delivery = !empty($data->customer->delivery)) && array_reduce([
            $data->customer->street,
            $data->customer->streetnumber,
            $data->customer->zip,
            $data->customer->city
        ], function ($current, $next) {
            return $current || empty(trim($next));
        }, false)) {
        throw new Exception("All address fields must be filled out when the delivery option is checked.");
    }

    if (!empty($street = $data->customer->street)) {
        $customer->street = $street;
    }
    if (!empty($streetnumber = $data->customer->streetnumber)) {
        $customer->streetnumber = $streetnumber;
    }
    if (!empty($zip = $data->customer->zip)) {
        $customer->zip = $zip;
    }
    if (!empty($city = $data->customer->city)) {
        $customer->city = $city;
    }
    if (!empty($phone = $data->customer->phone)) {
        $customer->phone = $phone;
    }

    $articles = Article::fetchAll($database);
    $extras = Extra::fetchAll($database);

    $orderItems = [];
    foreach ($data->order as $orderItem) {
        $article = $articles[$orderItem->id];
        if (empty($article)) {
            throw new Exception("Order article not found!");
        }
        $itemExtras = array_map(function ($id) use ($extras) {
            $extra = $extras[$id];
            if (empty($extra)) {
                throw new Exception("Order extra not found!");
            }
            return $extra;
        }, $orderItem->extras);

        try {
            $orderItems[] = new OrderItem($article, $itemExtras, $orderItem->quantity);
        } catch (Exception $e) {
            throw new Exception("Order extra not found!");
        }
    }

    $order = new Order($customer, $orderItems);

    if($delivery && $order->getPrice() < 10) {
        throw new Exception("Only orders with a total price of EUR 10 or more are eligible for delivery.");
    }

    $order->delivery = $delivery;
    Order::save($database, $order);
    echo json_encode((object)["error" => false]);
    exit(200);
} catch (Exception $e) {
    header('HTTP/1.1 400 Bad Request', false, 400);
    echo json_encode((object)["error" => true, "message" => $e->getMessage()]);
    exit(400);
}