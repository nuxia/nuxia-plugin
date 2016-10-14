<?php

namespace Nuxia\Component\Doctrine\Sortable;

use Nuxia\Component\Doctrine\Manager\AbstractEntityManager;

abstract class AbstractSortableManager extends AbstractEntityManager
{
    protected function doMove($entity, $direction, $alias)
    {
        if ($direction === 'up') {
            $sibling = $this->getRepository()->getPreviousEntity($entity, $alias);
        } else {
            $sibling = $this->getRepository()->getNextEntity($entity, $alias);
        }
        if ($sibling) {
            $old = $entity->getOrder();
            $new = $sibling->getOrder();
            $sibling->setOrder(-1);
            $this->em->persist($sibling);
            $this->em->flush();
            $entity->setOrder($new);
            $this->em->persist($entity);
            $sibling->setOrder($old);
            $this->em->persist($sibling);
            $this->em->flush();

            return true;
        }

        return false;
    }

    protected function getFlashBagType($doMove)
    {
        if ($doMove === true) {
            return 'success';
        }

        return 'danger';
    }
}
