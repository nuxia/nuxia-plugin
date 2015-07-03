<?php

namespace Nuxia\Component\Helper\ArrayUtil;

class ArrayHelper
{
    private function __construct()
    {

    }

    public static function isAssociativeArray(array $array)
    {
        return (is_array($array) && count(array_filter(array_keys($array), 'is_string')) == count($array));
    }

    public static function filterArrayByKeys(array $array, array $keys)
    {
        return array_intersect_key($array, array_fill_keys($keys, 'buffer'));
    }
}