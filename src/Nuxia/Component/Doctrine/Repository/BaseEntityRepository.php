<?php

namespace Nuxia\Component\Doctrine\Repository;

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Nuxia\Component\Parser;

class BaseEntityRepository extends DoctrineEntityRepository
{
    /**
     * @param QueryBuilder $qb
     * @param string $alias
     * @param array $criteria
     */
    protected function parseCriteria(QueryBuilder $qb, $alias, array $criteria)
    {
        $criteria = Parser::cleanArray($criteria);
        foreach ($criteria as $key => $value) {
            if (is_array($value)) {
                $qb->andWhere($this->arrayToClause($alias, $key, $value));
            } else {
                $qb->andWhere($this->generateWherePart($alias, $key, null, $this->getWhereOperator($value)));
                $qb->setParameter($alias . '_' . $key, $this->getWhereValue($value));
            }
        }
    }

    /**
     * @param string $alias
     * @param string $field
     * @param array $array
     * @param string $operator
     *
     * @return string
     */
    protected function arrayToClause($alias, $field, array $array, $operator = 'OR')
    {
        $first = array_shift($array);
        $clause = $this->generateWherePart($alias, $field, $first);
        foreach ($array as $value) {
            $clause .= ' ' . $this->resolveArrayToClauseOperator($operator, $array) . ' ' . $this->generateWherePart($alias, $field, $value);
        }
        return $clause;
    }

    /**
     * @param string $alias
     * @param array $criteria
     * @param string $operator
     * @return string
     */
    protected function criteriaToClause($alias, array $criteria, $operator = 'OR')
    {
        $count = count($criteria);
        $i = 1;
        $clause = '';
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $clause .= '(' . $this->arrayToClause($alias, $field, $value, $operator === 'OR' ? 'AND' : 'OR') . ')';
            } else {
                $clause .= $this->generateWherePart($alias, $field, $value);
            }
            if ($i !== $count) {
                $clause .= ' ' . $operator . ' ';
            }
            $i++;
        }
        return $clause;
    }

    /**
     * @param string $alias
     * @param string $field
     * @param string|null $value
     * @param string|null $operator
     * @return string
     */
    protected function generateWherePart($alias, $field, $value = null, $operator = null)
    {
        $clause = $alias . '.' . $field . ' ' . ($operator !== null ? $operator : $this->getWhereOperator(
                $value
            )) . ' ';
        $clause .= $value === null ? ':' . $alias . '_' . $field : '\'' . $this->getWhereValue($value) . '\'';
        return $clause;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function getWhereOperator($value)
    {
        return !is_string($value) || strpos($value, '%') === false ? '=' : 'LIKE';
    }

    /**
     * @param string|$value
     * @return string
     */
    protected function getWhereValue($value)
    {
        return $value;
    }

    /**
     * @param  string $operator
     * @param  array  $values
     *
     * @return string
     */
    protected function resolveArrayToClauseOperator($operator, $values)
    {
        return $operator;
    }

    /**
     * @param $qb
     * @param $alias
     * @param $criteria
     */
    protected function advancedParseCriteria(QueryBuilder $qb, $alias, array $criteria)
    {
        foreach ($criteria as $key => $value) {
            if (is_array($value) && isset($value['operator'])) {
                switch ($value['operator']) {
                    case 'between':
                        $qb->andWhere($alias . '.' . $key . ' BETWEEN :' . $key . '_start AND :' . $key . '_end');
                        $qb->setParameter($key . '_start', $value['start']);
                        $qb->setParameter($key . '_end', $value['end']);
                        break;
                    case '>': case '>=': case '<': case '<=': case '!=':
                    $qb->andWhere($alias . '.' . $key . ' '. $value['operator'] . ' :' . $key);
                    $qb->setParameter($key, $value['value']);
                    break;
                }
                unset($criteria[$key]);
            }
        }
        self::parseCriteria($qb, $alias, $criteria);
    }

    /**
     * @param QueryBuilder $qb
     * @param string $alias
     * @param string $key
     * @param array $criteria
     */
    protected function parseSubCriteria(QueryBuilder $qb, $alias, $key, array &$criteria)
    {
        //@REWORK ArrayAccess
        //$key pourrait être un property path pour accéder à des niveaux plus profonds
        if(isset($criteria[$key]) && is_array($criteria[$key])) {
            $this->parseCriteria($qb, $alias, $criteria[$key]);
            unset($criteria[$key]);
        }
    }
}