<?php

namespace Nuxia\Component\Doctrine\Manager;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @TODO a renommer AbstractManager
 */
abstract class AbstractEntityManager implements ManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository($entity = null)
    {
        if ($entity === null) {
            return $this->em->getRepository($this->getEntityName());
        }
        return $this->em->getRepository($entity);
    }
}
