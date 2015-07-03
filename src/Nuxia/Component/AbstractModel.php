<?php

namespace Nuxia\Component;

use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractModel implements ModelInterface
{
    /**
     * {@inheritdoc}
     */
    public function getModelName($format = 'underscore')
    {
        $reflection = new \ReflectionClass(get_class($this));
        $classname = $reflection->getShortName();
        switch ($format) {
            case 'lower':
                return strtolower($classname);
                break;
            case 'underscore':
                return Parser::underscore($classname);
                break;
            case 'camelize':
                return Parser::camelize($classname);
                break;
        }
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function getResourceLocation($format = 'bundle')
    {
        $reflection = new \ReflectionClass(get_class($this));
        $namespace = $reflection->getNamespaceName();
        switch ($format) {
            case 'bundle':
                return Parser::getBundleClass($namespace);
                break;
            case 'namespace':
                return $namespace;
                break;
        }
    }

    /**
     * @param $input
     * @param array  $fields
     *
     * @return AbstractModel
     */
    public function fromArrayOrObject($input, array $fields = array())
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
        $array = array();
        foreach ($fields as $field) {
            $array[$field] = $accessor->getValue($this, $field);
        }
        return $array;
    }
}