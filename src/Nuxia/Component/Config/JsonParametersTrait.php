<?php

namespace Nuxia\Component\Config;

use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * @TODO utiliser le builder pour abstraire les crochets?
 * @TODO getIntValue, getStringValue, getDateTimeValue et filter pour retourner le type souhaité
 * Parameters field management.
 * If you use Doctrine, the field type must be array, simple array or json_array
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
trait JsonParametersTrait {

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed                        $value
     */
    public function setParameter($propertyPath, $value)
    {
        $propertyPathAccessor = PropertyAccess::createPropertyAccessor();
        $propertyPathAccessor->setValue($this->parameters, $propertyPath, $value);
    }

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed|null                   $default       The default value if the parameter does not exist
     *
     * @return mixed
     */
    public function getParameter($propertyPath, $default = null)
    {
        $propertyPathAccessor = PropertyAccess::createPropertyAccessor();

        if ($propertyPathAccessor->isReadable($this->parameters, $propertyPath)) {
            return $propertyPathAccessor->getValue($this->parameters, $propertyPath);
        }

        return $default;
    }

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     * @param mixed|false                  $default       The default value if the parameter does not exist
     *
     * @return bool
     */
    public function getBooleanParameter($propertyPath, $default = false)
    {
        return filter_var($this->getParameter($propertyPath, $default), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param string|PropertyPathInterface $propertyPath  The property path to modify
     *
     * @throws NoSuchIndexException
     */
    public function removeParameter($propertyPath)
    {
        $propertyPathAccessor = PropertyAccess::createPropertyAccessor();

        if (!$propertyPath instanceof PropertyPathInterface) {
            $propertyPath = new PropertyPath($propertyPath);
        }

        if (1 === $propertyPath->getLength()) {
            $buffer = &$this->parameters;
            unset($buffer[$propertyPath->getElement(0)]);
        } else {
            $parentPropertyPath = $propertyPath->getParent();
            $buffer = $propertyPathAccessor->getValue($this->parameters, $parentPropertyPath);
            unset($buffer[$propertyPath->getElement($propertyPath->getLength() - 1)]);
            $propertyPathAccessor->setValue($this->parameters, $parentPropertyPath, $buffer);
        }
    }
}
