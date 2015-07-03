<?php

namespace Nuxia\Component;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

//@REWORK a depacer dans EventDispatcher
interface EventDispatcherAwareInterface
{
    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher);
}
