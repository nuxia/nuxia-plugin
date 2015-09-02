<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

/**
 * @TODO not implemented yet
 * This extension allows you to "houralize" a \Datetime object or a string representing hour (hh:mm) from twig templates
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class DatetimeExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('houralize', array($this, 'houralize')),
        );
    }

    /**
     * @param $minute
     * @return string
     */
    public function houralize($minute)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'datetime';
    }
}