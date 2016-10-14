<?php

namespace Nuxia\Component\Pager\EventListener;

use Doctrine\ORM\Query;
use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SortListener implements EventSubscriberInterface
{
    public function items(ItemsEvent $event)
    {
        if ($event->target instanceof Query) {
            $event->options['sortFieldParameterName'];
            $event->options['sortFieldParameterName'];

            $buffer = $event->target->getDQLPart('orderBy');
            $default_order = explode(' ', $buffer[0]);
            $paginator->setParam($paginator->getPaginatorOption('sortFieldParameterName'), $default_order[0]);
            $paginator->setParam($paginator->getPaginatorOption('sortDirectionParameterName'), $default_order[1]);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'knp_pager.items' => ['items', 0],
        ];
    }
}
