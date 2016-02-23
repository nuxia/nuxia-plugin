<?php

namespace Nuxia\Component\Config;

use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
interface JsonParametersInterface {

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed                        $value
     */
    public function setParameter($propertyPath, $value);

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed|null                   $default       The default value if the parameter does not exist
     *
     * @return mixed
     */
    public function getParameter($propertyPath, $default = null);

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed|false                  $default       The default value if the parameter does not exist
     *
     * @return bool
     */
    public function getBooleanParameter($propertyPath, $default = false);
}
