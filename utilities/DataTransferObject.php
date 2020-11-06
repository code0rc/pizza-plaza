<?php


namespace PizzaPlaza\Utilities;


use ReflectionClass;
use ReflectionProperty;
use stdClass;

class DataTransferObject
{
    /**
     * @param stdClass $data
     * @return static
     * @throws InvalidDataTransferObjectException
     */
    public static function fromStdClass(stdClass $data) {
        $dto = new static();
        $rc = new ReflectionClass($dto);
        foreach ($rc->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            if(!isset($dto->$propertyName) && !isset($data->$propertyName)) {
                throw new InvalidDataTransferObjectException();
            }

            if(isset($data->$propertyName)) {
                $dto->$propertyName = $data->$propertyName;
            }
        }

        return $dto;
    }

    /**
     * @param array $data
     * @return static
     * @throws InvalidDataTransferObjectException
     */
    public static function fromAssociativeArray(array $data)
    {
        return self::fromStdClass((object)$data);
    }
}