<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

class HtmlExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('concat_attribute', array($this, 'concatAttribute')),
        );
    }

    /**
     * @param  string       $value
     * @param  string|array $join
     *
     * @return string
     */
    public function concatAttribute($value, $join)
    {
        if (is_array($join)) {
            $join = implode(' ', $join);
        }
        if (empty($join)) {
            return $value;
        } elseif (empty($value)) {
            return $join;
        } else {
            return $value . ' ' . $join;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'html';
    }
}