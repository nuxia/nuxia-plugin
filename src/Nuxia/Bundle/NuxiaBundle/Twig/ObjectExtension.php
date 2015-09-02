<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Nuxia\Component\Parser;

class ObjectExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'field' => new \Twig_Filter_Method($this, 'field'),
        );
    }

    /**
     * @param  object $object
     * @param  string $field
     *
     * @return mixed
     */
    public function field($object, $field)
    {
        //@TODO refactoring with routing system
        $buffer = strpos($field, '_') === false ? array($field) : (explode('_', $field));
        $value = $object;
        foreach ($buffer as $segment) {
            $getter = 'get' . Parser::camelize($segment);
            $value = $value->$getter();
        }
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'object';
    }
}