<?php

namespace Nuxia\Component\Console;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;

abstract class AbstractDoctrineCommand extends AbstractCommand
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    private $totalFlushSize;

    private $maxFlushSize = null;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function setManagerRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    protected function setTotalFlushSize($totalFlushSize)
    {
        $this->totalFlushSize = $totalFlushSize;
    }

    protected function getTotalFlushSize()
    {
        return $this->totalFlushSize;
    }

    protected function setMaxFlushSize($maxFlushSize)
    {
        $this->maxFlushSize = $maxFlushSize;
    }

    public function decrementTotalFlushSize()
    {
        --$this->totalFlushSize;
    }

    //@REWORK passer en PersistHelper?
    protected function persistObject($object, ObjectManager $manager)
    {
        $manager->persist($object);
        if ($this->maxFlushSize !== null) {
            $this->decrementTotalFlushSize();
            if (($this->totalFlushSize % $this->maxFlushSize) === 0 || $this->totalFlushSize === 0) {
                $manager->flush();
                $manager->clear();
            }
        }
    }

    protected function truncateTable($class, $connection = null)
    {
        //@TODO Comprendre comment marche exactement getManager et récupérer le manager avec la class
        $tableName = $this->getTableName($class, $connection);
        $connection = $this->managerRegistry->getConnection($connection);
        $connection->exec('DELETE FROM ' . $tableName);
    }

    protected function getTableName($class, $connection = null)
    {
        return $this->managerRegistry->getManager($connection)->getClassMetadata($class)->getTableName();
    }
}
