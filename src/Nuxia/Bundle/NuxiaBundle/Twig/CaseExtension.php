<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class CaseExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'camelize' => new \Twig_Filter_Method($this, 'camelize'),
            'underscore' => new \Twig_Filter_Method($this, 'underscore'),
        );
    }

    public function camelize($text)
    {
        return Container::camelize($text);
    }

    public function underscore($text)
    {
        return Container::underscore($text);
    }

    public function getName()
    {
        return 'case';
    }
}