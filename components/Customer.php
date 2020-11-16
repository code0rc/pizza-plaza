<?php


namespace PizzaPlaza\Components;


use Exception;

class Customer
{
    public $firstname = null;
    public $lastname = null;
    public $street = null;
    public $streetnumber = null;
    public $zip = null;
    public $city = null;
    public $phone = null;

    /**
     * Customer constructor.
     * @param string $firstname
     * @param string $lastname
     * @throws Exception
     */
    public function __construct(string $firstname, string $lastname)
    {
        if(empty(trim($firstname)) || empty(trim($lastname))) {
            throw new Exception("Missing customer information");
        }
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }
}