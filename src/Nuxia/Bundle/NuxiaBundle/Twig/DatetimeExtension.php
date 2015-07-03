<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

//@TODO a implementer
class DatetimeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'houralize' => new \Twig_Filter_Method($this, 'houralize'),
        );
    }

    /**
     * @param $minute
     * @return string
     */
    public function houralize($minute)
    {

    }

    public function getName()
    {
        return 'datetime';
    }
}