<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Nuxia\Component\Parser;

/**
 * //@TODO Check if attribute function already provide this feature? and use propertyPathAccessor instead of own propertyPath
 * This extension adds a filter to traverse an object (as property path component does)
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class ObjectExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('field', array($this, 'field')),
        );
    }

    /**
     * @param object $object
     * @param string $field
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
