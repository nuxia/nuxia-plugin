<?php

namespace Nuxia\Component\Helper\ResourceUtil;

class ResourceHelper
{
    private function __construct()
    {
    }
    //@TODO nom a revoir
    public static function getBundleClass($namespace)
    {
        $buffer = explode('\\', $namespace);

        return $buffer[0] . '\\' . $buffer[1] . '\\' . $buffer[0] . $buffer[1];
    }

    //@TODO nom a revoir
    public function getResourceLocation($object, $format = 'bundle')
    {
        $reflection = new \ReflectionClass(get_class($object));
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
