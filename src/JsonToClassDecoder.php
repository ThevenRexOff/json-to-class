<?php

namespace Thevenrex\JsonToClass;

use Exception;

use function gettype;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

use function sprintf;

class JsonToClassDecoder
{
    public function __construct(
        private $json,
        protected ?ReflectionProperty $property = null
    ) {
        $this->json = json_decode($this->json, true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * Decode the JSON string to the given object.
     * @param object $object The object to decode the JSON string to.
     * @return void
     * @throws Exception If a property type is invalid.
     */
    public function decode(object $object): void
    {
        $reflection = new ReflectionClass($object);

        foreach ($reflection->getProperties() as $property) {
            $this->property = $property;
            $this->property->setAccessible(true);
            $propertyName = $this->property->getName();
            $propertyType = $this->property->getType();

            if ($propertyType->isBuiltin()) {
                $this->setPrimitiveValue($object, $this->json[$propertyName]);
            } else {
                $this->setObjectValue($object, $this->json[$propertyName]);
            }
        }
    }

    /**
     * Set the object value of the current property.
     * @param object $object The object to set the value to.
     * @param mixed $value The value to set.
     * @return void
     * @throws JsonException If a sub-property type is invalid.
     */
    private function setObjectValue(object $object, mixed $value): void
    {
        $subClass = $this->property->getType()->getName();
        $subObject = new $subClass();
        $new = new self(json_encode($value));
        $new->decode($subObject);
        $this->property->setValue($object, $subObject);
    }

    /**
     * Set the primitive value of the current property.
     * @param object $object The object to set the value to.
     * @param mixed $value The value to set.
     * @return void
     * @throws JsonException If the type of the value is invalid.
     */
    private function setPrimitiveValue(object $object, mixed $value): void
    {
        $type = $this->property->getType();

        if ($type instanceof ReflectionNamedType) {
            $type = $type->getName();
        }

        $valueType = $this->getType($value);

        if ($valueType != $type) {
            throw new JsonException(sprintf('Invalid type for property %s, expected %s, given %s', $this->property->getName(), $type, $valueType));
        }

        $this->property->setValue($object, $value);
    }

    /**
     * Get the type of a value.
     * @param mixed $value The value to get the type of.
     * @return string The type of the value.
     */
    public function getType($value): string
    {

        $type = gettype($value);

        return match ($type) {
            'integer' => 'int',
            'double' => 'float',
            'boolean' => 'bool',
            default => $type,
        };
    }
}
