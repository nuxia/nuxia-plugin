<?php

namespace Nuxia\Component\Helper\DateUtil;

class DateManipulator
{
    private function __construct()
    {

    }

    //@TODO nom a revoir
    /**
     * @param \Datetime $startedAt
     * @param \Datetime $endedAt
     *
     * @return array
     */
    public static function toFilter(\Datetime $startedAt, \Datetime $endedAt)
    {
        return array(
            'operator' => 'between',
            'start' => $startedAt->setTime(0, 0, 0),
            'end' => $endedAt->setTime(23, 59, 59)
        );
    }


}