<?php

namespace Nuxia\Component\Form\EventListener;

use Nuxia\Component\Parser;
use Symfony\Component\Form\FormEvent;

class CleanArrayDataEvent
{
    public function cleanArrayData(FormEvent $event)
    {
        $event->setData(Parser::cleanArray($event->getData(), 'all'));
    }
}
