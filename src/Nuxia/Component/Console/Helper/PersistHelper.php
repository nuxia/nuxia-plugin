<?php

namespace Nuxia\Component\Console\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Templating\Helper\Helper;

//@REWORK objectManager en field de la classe?
class PersistHelper extends Helper
{
    /**
     * @var int
     */
    private $totalFlushSize;

    /**
     * @var int
     */
    private $maxFlushSize;

    /**
     * @param int $maxFlushSize
     */
    public function __construct($totalFlushSize, $maxFlushSize = 500)
    {
        $this->totalFlushSize = $totalFlushSize;
        $this->maxFlushSize = $maxFlushSize;
    }

    /**
     * @return int
     */
    public function getTotalFlushSize()
    {
        return $this->totalFlushSize;
    }

    /**
     * @param ObjectManager $manager
     */
    public function decrementTotalFlushSize(ObjectManager $manager)
    {
        $this->totalFlushSize--;
        if (($this->totalFlushSize % $this->maxFlushSize) === 0 || $this->totalFlushSize === 0) {
            $manager->flush();
            $manager->clear();
        }
    }

    /**
     * @param $entity
     * @param ObjectManager $manager
     */
    public function persist($entity, ObjectManager $manager)
    {
        $manager->persist($entity);
        $this->decrementTotalFlushSize($manager);
    }

    /**
     * @param $entity
     * @param ObjectManager $manager
     */
    public function remove($entity, ObjectManager $manager)
    {
        $manager->remove($entity);
        $this->decrementTotalFlushSize($manager);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'persist';
    }
}