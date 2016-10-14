<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

/**
 * This extension allows you to camelize (ExampleString) and to underscore (example_string) strings from twig templates.
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class CaseExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('camelize', [$this, 'camelize']),
            new \Twig_SimpleFilter('underscore', [$this, 'underscore']),
        ];
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function camelize($text)
    {
        return Container::camelize($text);
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function underscore($text)
    {
        return Container::underscore($text);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'case';
    }
}
