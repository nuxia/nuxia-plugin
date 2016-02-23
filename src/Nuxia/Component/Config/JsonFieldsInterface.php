<?php

namespace Nuxia\Component\Config;

use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
interface JsonFieldsInterface {

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed                        $value
     */
    public function setField($propertyPath, $value);

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed|null                   $default       The default value if the field does not exist
     *
     * @return mixed
     */
    public function getField($propertyPath, $default = null);

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed|false                  $default       The default value if the field does not exist
     *
     * @return bool
     */
    public function getBooleanField($propertyPath, $default = false);
}
