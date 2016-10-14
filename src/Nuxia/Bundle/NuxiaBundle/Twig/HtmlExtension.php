<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

/**
 * This extension adds usefull html tools on twig template :
 * - concat_attribute (@TODO rename) : @TODO to document
 * - render_html_attributes : convert array to html attributes.
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class HtmlExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('concat_attribute', [$this, 'concatAttribute']),
        ];
    }

    /**
     * @param string       $value
     * @param string|array $join
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

    //@TODO render html attributes from array (@see form_div_layout)
    public function renderAttributes()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'html';
    }
}
