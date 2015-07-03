<?php

namespace Nuxia\Component\Doctrine\Sortable;

use Nuxia\Component\Doctrine\Repository\BaseEntityRepository;
use Nuxia\Component\Parser;

//@REWORK étendre le behavior de gedmo
abstract class AbstractSortableRepository extends BaseEntityRepository
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @return array
     */
    abstract protected function initSortableConfiguration();

    /**
     * @return array
     */
    public function getSortableConfiguration()
    {
        if ($this->config === null) {
            $this->config = $this->initSortableConfiguration();
        }
        return $this->config;
    }

    /**
     * @param object $entity
     * @param string $alias
     *
     * @return string|null
     */
    protected function getExtraOrderableClause($entity, $alias)
    {
        return null;
    }

    //@TODO a renommer en getOrderableQueryBuilder
    /**
     * @param object $entity
     * @param string $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getOrderableQuery($entity, $alias)
    {
        $this->getSortableConfiguration();
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->from($this->getEntityName(), $alias);
        foreach ($this->config['groups'] as $field) {
            $getter = 'get' . Parser::camelize($field);
            $qb->andWhere($alias . '.' . $field . ' = :' . $field);
            $qb->setParameter($field, $entity->$getter());
        }
        $extraClause = $this->getExtraOrderableClause($entity, $alias);
        if ($extraClause !== null) {
            $qb->andWhere($extraClause);
        }
        return $qb;
    }

    /**
     * @param object $entity
     * @param string $alias
     *
     * @return object|null
     */
    public function getNextEntity($entity, $alias)
    {
        $qb = $this->getOrderableQuery($entity, $alias);
        $qb->select($alias);
        $qb->andWhere($alias . '.' . $this->config['position'] . ' > :position');
        $getter = 'get' . Parser::camelize($this->config['position']);
        $qb->setParameter('position', $entity->$getter());
        $qb->setMaxResults(1);
        $qb->orderBy($alias . '.order', 'ASC');
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param object $entity
     * @param string $alias
     *
     * @return object|null
     */
    public function getPreviousEntity($entity, $alias)
    {
        $qb = $this->getOrderableQuery($entity, $alias);
        $qb->select($alias);
        $qb->andWhere($alias . '.' . $this->config['position'] . ' < :position');
        $getter = 'get' . Parser::camelize($this->config['position']);
        $qb->setParameter('position', $entity->$getter());
        $qb->orderBy($alias . '.order', 'DESC');
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param object $entity
     * @param string $alias
     *
     * @return int
     */
    public function getNewOrder($entity, $alias)
    {
        $qb = $this->getOrderableQuery($entity, $alias);
        $qb->select('MAX(' . $alias . '.' . $this->config['position'] . ')');
        $order = $qb->getQuery()->getSingleScalarResult();
        return intval($order) + 1;
    }

    //@DEPRECATED Utiliser SortableBehavior à la place
    //@REWORK voir pour factoriser avec getNewOrder ou surcharge gedmo (à noter que cette méthode n'est aujourd'hui qu'utilisable pour le create)
    /**
     * @param string $alias
     * @param array $conditions
     *
     * @return int
     */
    public function getOrderWhenCreate($alias, $conditions = array())
    {
        //@REWORK $conditions = criteria et getCriteriaFromEntity
        $this->getSortableConfiguration();
        $qb = $this->createQueryBuilder($alias);
        $qb->select('MAX(' . $alias . '.' . $this->config['position'] . ')');
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $qb->andWhere($alias . '.' . $field. ' = :' . $field);
                $qb->setParameter($field, $value);
            }
        }
        $order = $qb->getQuery()->getSingleScalarResult();
        return intval($order) + 1;
    }
}
