<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule\FilterRuleBoolean;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface as DoctrineORMQueryObjectInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 * @author Stepan Koci <stepan.koci@imatic.cz>
 */
class DisplayCriteriaQueryBuilder
{
    /**
     * @param QueryBuilder $qb
     * @param DoctrineORMQueryObjectInterface $queryObject
     * @param DisplayCriteriaInterface $displayCriteria
     */
    public function apply(QueryBuilder $qb, DoctrineORMQueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if ($displayCriteria === null) {
            return;
        }

        $this->applyPager($qb, $displayCriteria->getPager());
        $this->applyFilter($qb, $displayCriteria->getFilter(), $queryObject);
        $this->applySorter($qb, $displayCriteria->getSorter(), $queryObject);
    }

    /**
     * @param QueryBuilder $qb
     * @param PagerInterface $pager
     */
    private function applyPager(QueryBuilder $qb, PagerInterface $pager)
    {
        $qb
            ->setFirstResult($pager->getOffset())
            ->setMaxResults($pager->getLimit());
    }

    /**
     * @param QueryBuilder $qb
     * @param FilterInterface $filter
     * @param DoctrineORMQueryObjectInterface $queryObject
     * @throws \InvalidArgumentException
     */
    private function applyFilter(QueryBuilder $qb, FilterInterface $filter, DoctrineORMQueryObjectInterface $queryObject)
    {
        if ($queryObject instanceof FilterableQueryObjectInterface) {
            $filterMap = $queryObject->getFilterMap();

            /* @var $filterRule FilterRule */
            foreach ($filter as $filterRule) {
                // Rule must be bound
                if (!$filterRule->isBound()) {
                    continue;
                }

                // Rule must be presented in filter map
                if (!isset($filterMap[$filterRule->getName()])) {
                    throw new \InvalidArgumentException(sprintf('Column "%s" is not presented in filter map', $filterRule->getName()));
                }

                $this->addFilterRule($qb, $filterRule, $filterMap[$filterRule->getName()]);
            }
        }
    }

    private function addFilterRule(QueryBuilder $qb, FilterRule $rule, $column)
    {
        if ($column instanceof \Closure) {
            $column($qb, $rule);
        } elseif ($rule instanceof FilterRuleBoolean) {
            if (FilterOperatorMap::OPERATOR_EMPTY === $rule->getOperator()) {
                $qb->andWhere($qb->expr()->isNull($column));
            } else {
                switch ($rule->getValue()) {
                    case FilterRuleBoolean::NO:
                    case false:
                        $qb->andWhere($qb->expr()->eq($column, 'false'));
                        break;
                    case FilterRuleBoolean::YES_NO:
                        $qb->andWhere($qb->expr()->isNotNull($column));
                        break;
                    default:
                        $qb->andWhere($qb->expr()->eq($column, 'true'));
                }
            }
        } else {
            $param = ':' . $rule->getName();
            $name = $rule->getName();
            switch ($rule->getOperator()) {
//                case FilterOperatorMap::OPERATOR_BETWEEN:
//                    $ex->gte($column, $param . 'From');
//                    $ex->lte($column, $param . 'To');
//                    $qb->setParameter($name . 'From', $rule->getValue()['from']);
//                    $qb->setParameter($name . 'To', $rule->getValue()['to']);
//                    break;
//                case FilterOperatorMap::OPERATOR_NOT_BETWEEN:
//                    $ex->lte($column, $param . 'From');
//                    $ex->gte($column, $param . 'To');
//                    $qb->setParameter($name . 'From', $rule->getValue()['from']);
//                    $qb->setParameter($name . 'To', $rule->getValue()['to']);
//                    break;
                case FilterOperatorMap::OPERATOR_CONTAINS:
                case FilterOperatorMap::OPERATOR_NOT_CONTAINS:
                    $qb->andWhere($qb->expr()->{$rule->getOperator()}($column, $param));
                    $qb->setParameter($name, '%' . $rule->getValue() . '%');
                    break;
                case FilterOperatorMap::OPERATOR_EMPTY:
                    $qb->andWhere($qb->expr()->isNull($column));
                    break;
                case FilterOperatorMap::OPERATOR_NOT_EMPTY:
                    $qb->andWhere($qb->expr()->isNotNull($column));
                    break;
                default:
                    $qb->andWhere($qb->expr()->{$rule->getOperator()}($column, $param));
                    $qb->setParameter($name, $rule->getValue());
            }
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param SorterInterface $sorter
     * @param DoctrineORMQueryObjectInterface $queryObject
     * @throws \InvalidArgumentException
     */
    private function applySorter(QueryBuilder $qb, SorterInterface $sorter, DoctrineORMQueryObjectInterface $queryObject)
    {
        if ($queryObject instanceof SortableQueryObjectInterface) {
            $sorterMap = $queryObject->getSorterMap();

            // Default sorting if no sorter rules exists
            if (0 === $sorter->count() && 0 < count($queryObject->getDefaultSort())) {
                $sorter->setSorterRules($queryObject->getDefaultSort());
            }

            /* @var $sorterRule SorterRule */
            foreach ($sorter as $sorterRule) {
                if (!isset($sorterMap[$sorterRule->getColumn()])) {
                    throw new \InvalidArgumentException(sprintf('Column "%s" is not presented in sorter map', $sorterRule->getColumn()));
                }

                $qb->addOrderBy($sorterMap[$sorterRule->getColumn()], $sorterRule->getDirection());
            }
        }
    }
}
