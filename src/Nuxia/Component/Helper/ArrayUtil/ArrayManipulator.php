<?php

namespace Nuxia\Component\Helper\ArrayUtil;

use Symfony\Component\PropertyAccess\PropertyAccess;

class ArrayManipulator
{
    private function __construct()
    {

    }

    public static function prefixArray(array $input, $prefix)
    {
        $output = array();
        $isAssociativeArray = ArrayHelper::isAssociativeArray($input);
        $buffer = $isAssociativeArray ? array_values($input) : $input;
        foreach ($buffer as $value) {
            $output = $prefix . $value;
        }
        $output = $isAssociativeArray ? array_combine(array_keys($input), $output) : $output;
        return $output;
    }

    public static function cleanArray(array $array, $type = 'default')
    {
        switch ($type) {
            case 'all':
                $callback = function ($v) {
                    return !($v === null || $v === '' || $v === array());
                };
                break;
            default:
                $callback = function ($v) {
                    return !($v === null || $v === '');
                };
        }
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = self::cleanArray($value);
            }
        }
        return array_filter($array, $callback);
    }

    //@REWORK Utilisation d'un ArrayAccessor?
    public static function setValue($array, $propertyPath, $value)
    {
        $resolvedPropertyPath = self::resolvePropertyPath($propertyPath, '.');
        if ($array === null) {
            $array = array();
        }
        return PropertyAccess::createPropertyAccessor()->setValue($array, $resolvedPropertyPath, $value);
    }

     public static function getValue($array, $propertyPath, $throwExceptionOnInvalidIndex = false)
     {
         $accessor = PropertyAccess::createPropertyAccessor(false, $throwExceptionOnInvalidIndex);
         $resolvedPropertyPath = self::resolvePropertyPath($propertyPath, '.');
         if ($array === null) {
             $array = array();
         }
         return PropertyAccess::createPropertyAccessor()->getValue($array, $resolvedPropertyPath);
     }

     //@REWORK Utilisation d'un système pour formatter les paths. Ce système est utilisé dans le router et dans admin
    private static function resolvePropertyPath($propertyPath, $separator = '.')
    {
        $resolvedPropertyPath = '';
        foreach (explode('.', $propertyPath) as $subPath) {
            $resolvedPropertyPath .= '[' . $propertyPath . ']';
        }
        return $resolvedPropertyPath;
    }
}