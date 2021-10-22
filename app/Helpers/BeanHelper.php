<?php

namespace App\Helpers;

use Linuzilla\Form\Attributes\Categories\ConverterAttribute;
use ReflectionClass;
use ReflectionProperty;

class BeanHelper {
    private ReflectionClass $ref;
    /** @var ReflectionProperty[] $properties */
    private array $properties;

    public function __construct(private object $bean) {
        $this->ref = new ReflectionClass($bean);

        $this->properties = array_reduce($this->ref->getProperties(), function ($carry, $property) {
            $carry[$property->name] = $property;
            return $carry;
        });
    }

    /**
     * @param array $data
     * @return object
     */
    public function updateBean(array $data): object {
        foreach ($this->properties as $name => $attr) {
            if (isset($data[$name])) {
                $attr->setValue($this->bean, $data[$name]);
            }
        }
        return $this->bean;
    }
}
