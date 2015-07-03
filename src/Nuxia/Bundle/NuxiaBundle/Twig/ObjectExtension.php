<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Nuxia\Component\Parser;

class ObjectExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'field' => new \Twig_Filter_Method($this, 'field'),
        );
    }

    public function field($object, $field)
    {
        //@TODO a opti avec le routing (même système)
        $buffer = strpos($field, '_') === false ? array($field) : (explode('_', $field));
        $value = $object;
        foreach ($buffer as $segment) {
            $getter = 'get' . Parser::camelize($segment);
            $value = $value->$getter();
        }
        return $value;
    }

    public function getName()
    {
        return 'object';
    }
}