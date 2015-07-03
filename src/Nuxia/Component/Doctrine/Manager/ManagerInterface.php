<?php

namespace Nuxia\Component\Doctrine\Manager;

use Doctrine\ORM\EntityRepository;

interface ManagerInterface
{
    /**
     * @param EntityRepository $entity
     *
     * @return mixed
     */
    public function getRepository($entity = null);

    //@TODO getClassName?
    /**
     * @return string
     */
    public function getEntityName();
}
