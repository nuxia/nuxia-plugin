<?php

namespace Nuxia\Component;

use Nuxia\Component\Helper\ModelUtil\ModelTrait;

abstract class AbstractModel implements ModelInterface
{
    use ModelTrait;

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
}
