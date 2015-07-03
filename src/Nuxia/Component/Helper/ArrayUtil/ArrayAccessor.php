<?php

namespace Nuxia\Component\Helper\ArrayUtil;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ArrayAccessor extends PropertyAccessor
{
    public function setValue(&$objectOrArray, $propertyPath, $value)
    {
        $propertyPath = $this->buildArrayPropertPath($propertyPath);
        return parent::setValue($objectOrArray, $propertyPath, $value);
    }

    public function getValue($objectOrArray, $propertyPath)
    {
        $propertyPath = $this->buildArrayPropertPath($propertyPath);
        return parent::getValue($objectOrArray, $propertyPath);
    }

    public function hasProperty($objectOrArray, $propertyPath)
    {
        try {
            $this->getValue($objectOrArray, $propertyPath);
        } catch (NoSuchPropertyException $e) {
            return false;
        }
        return true;
    }

    protected function buildArrayPropertPath($path, $separator = '.')
    {
        $propertyPath = '';
        foreach (explode($separator, $path) as $subPath) {
            $propertyPath = '[' . $subPath . ']';
        }
        return $propertyPath;
    }

    //@REWORK
    //@TODO a revoir quand on spécifie la proprieté ou pas
    public static function buildPropertyPath($path, $separator = '.')
    {
        $propertyPath = '';
        foreach (explode($separator, $path) as $subPath) {
            $propertyPath .= '[' . $subPath . ']';
        }
        return $propertyPath;
    }

}