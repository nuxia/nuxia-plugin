<?php

namespace Nuxia\Component\Helper\ModelUtil;

use Symfony\Component\PropertyAccess\PropertyAccess;

class ModelManipulator
{
    private function __construct()
    {
    }

    public function populateFromArrayOrObject($object, $arrayOrObject, array $fields = [])
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $buffer = is_object($arrayOrObject) ? self::toArray($arrayOrObject, $fields) : $arrayOrObject;
        if (count($fields) === 0) {
            $fields = array_keys($arrayOrObject);
        }
        foreach ($fields as $field) {
            $accessor->setValue($object, $field, $accessor->getValue($buffer, '[' . $field . ']'));
        }

        return $object;
    }

    public function toArray($object, array $fields)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $array = [];
        foreach ($fields as $field) {
            $array[$field] = $accessor->getValue($object, $field);
        }

        return $array;
    }
}
