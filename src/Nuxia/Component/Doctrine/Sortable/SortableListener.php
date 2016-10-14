<?php

namespace Nuxia\Component\Doctrine\Sortable;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Nuxia\Component\Parser;

class SortableListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [Events::prePersist];
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof SortableInterface) {
            $em = $eventArgs->getEntityManager();
            $repo = $em->getRepository($em->getClassMetadata(get_class($entity))->getName());
            $config = $repo->getSortableConfiguration();
            $setter = 'set' . Parser::camelize($config['position']);
            $getter = 'get' . Parser::camelize($config['position']);
            if ($entity->$getter() === null) {
                $entity->$setter($repo->getNewOrder($entity, 'sort'));
            }
        }
    }
}
