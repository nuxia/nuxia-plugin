<?php

namespace Nuxia\Component\Validator\Helper;

class ValidatorUtil
{
    /**
     * @param $value
     *
     * @return bool
     */
    public static function isInteger($value)
    {
        return $value === (string) intval($value);
    }
}
