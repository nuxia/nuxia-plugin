<?php

namespace Nuxia\Component\Config;

use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * @TODO utiliser le builder pour abstraire les crochets?
 * @TODO getIntValue, getStringValue, getDateTimeValue et filter pour retourner le type souhaité
 * Fields field management.
 * If you use Doctrine, the field type must be array, simple array or json_array
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
trait JsonFieldsTrait
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string|PropertyPathInterface $propertyPath The property path to modify
     * @param mixed                        $value
     *
     * @throws NoSuchIndexException
     */
    public function setField($propertyPath, $value)
    {
        $propertyPathAccessor = PropertyAccess::createPropertyAccessor();
        $propertyPathAccessor->setValue($this->fields, $propertyPath, $value);
    }

    /**
     * @param string|PropertyPathInterface $propertyPath The property path to modify
     * @param mixed|null                   $default      The default value if the field does not exist
     *
     * @throws NoSuchIndexException
     *
     * @return mixed
     */
    public function getField($propertyPath, $default = null)
    {
        $propertyPathAccessor = PropertyAccess::createPropertyAccessorBuilder()->enableExceptionOnInvalidIndex()->getPropertyAccessor();

        if ($propertyPathAccessor->isReadable($this->fields, $propertyPath)) {
            return $propertyPathAccessor->getValue($this->fields, $propertyPath);
        }

        return $default;
    }

    /**
     * @param string|PropertyPathInterface $propertyPath The property path to modify
     * @param mixed|false                  $default      The default value if the field does not exist
     *
     * @return bool
     */
    public function getBooleanField($propertyPath, $default = false)
    {
        return filter_var($this->getField($propertyPath, $default), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param string|PropertyPathInterface $propertyPath The property path to modify
     *
     * @throws NoSuchIndexException
     */
    public function removeField($propertyPath)
    {
        $propertyPathAccessor = PropertyAccess::createPropertyAccessor();

        if (!$propertyPath instanceof PropertyPathInterface) {
            $propertyPath = new PropertyPath($propertyPath);
        }

        if (1 === $propertyPath->getLength()) {
            $buffer = &$this->fields;
            unset($buffer[$propertyPath->getElement(0)]);
        } else {
            $parentPropertyPath = $propertyPath->getParent();
            $buffer = $propertyPathAccessor->getValue($this->fields, $parentPropertyPath);
            unset($buffer[$propertyPath->getElement($propertyPath->getLength() - 1)]);
            $propertyPathAccessor->setValue($this->fields, $parentPropertyPath, $buffer);
        }
    }
}
