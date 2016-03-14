<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

/**
 * This extension allows you to camelize (ExampleString) and to underscore (example_string) strings from twig templates
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class CaseExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('camelize', array($this, 'camelize')),
            new \Twig_SimpleFilter('underscore', array($this, 'underscore')),
        );
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
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'case';
    }
}
