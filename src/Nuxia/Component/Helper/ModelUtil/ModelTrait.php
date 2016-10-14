<?php

namespace Nuxia\Component\Helper\ModelUtil;

use Symfony\Component\PropertyAccess\PropertyAccess;

trait ModelTrait
{
    /**
     * @param $input
     * @param array $fields
     *
     * @return object
     */
    public function fromArrayOrObject($input, array $fields = [])
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $isArray = is_array($input);
        if ($isArray && count($fields) === 0) {
            $fields = array_keys($input);
        }
        foreach ($fields as $field) {
            $path = $isArray ? '[' . $field . ']' : $field;
            $accessor->setValue($this, $field, $accessor->getValue($input, $path));
        }

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    public function toArray(array $fields)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $array = [];
        foreach ($fields as $field) {
            $array[$field] = $accessor->getValue($this, $field);
        }

        return $array;
    }
}
