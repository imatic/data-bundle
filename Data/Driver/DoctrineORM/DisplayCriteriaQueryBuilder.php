<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DisplayCriteriaQueryBuilder
{
    private static $operatorMap = [
        'equal' => '=',
        'not-equal' => '<>',
        'in' => 'IN',
        'contains' => 'LIKE',
        'empty' => 'IS NULL',
    ];

    /**
     * @param QueryBuilder $qb
     * @param DisplayCriteriaInterface $displayCriteria
     */
    public function apply(QueryBuilder $qb, DisplayCriteriaInterface $displayCriteria = null)
    {
        if ($displayCriteria === null) {
            return;
        }

        $this->applyPager($qb, $displayCriteria->getPager());
        $this->applyFilter($qb, $displayCriteria->getFilter());
        $this->applySorter($qb, $displayCriteria->getSorter());
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
     */
    private function applyFilter(QueryBuilder $qb, FilterInterface $filter)
    {
        /* @var $filterRule FilterRule */
        foreach ($filter as $filterRule) {
            $parameterName = str_replace('.', '_', $filterRule->getColumn());

            $dqlPart = sprintf('%s %s %s',
                $this->getFilterColumnDqlPart($filterRule, $qb),
                self::$operatorMap[$filterRule->getOperator()],
                $this->getParameterDqlPart($parameterName, $filterRule->getValue())
            );

            $qb->andWhere($dqlPart);
            $qb->setParameter($parameterName, $filterRule->getValue());
        }
    }

    /**
     * @param FilterRule $filterRule
     * @param QueryBuilder $qb
     * @return string
     */
    private function getFilterColumnDqlPart(FilterRule $filterRule, QueryBuilder $qb)
    {
        if (strpos($filterRule->getColumn(), '.') === false) {
            return $this->getPrefixedColumnWithRootEntityAlias($filterRule->getColumn(), $qb);
        }

        return $filterRule->getColumn();
    }

    /**
     * @param string $parameterName
     * @param mixed $filterValue
     * @return string
     */
    private function getParameterDqlPart($parameterName, $filterValue)
    {
        $parameter = ':' . $parameterName;

        return is_array($filterValue) ? sprintf('(%s)', $parameter) : $parameter;
    }

    /**
     * @param QueryBuilder $qb
     * @param SorterInterface $sorter
     */
    private function applySorter(QueryBuilder $qb, SorterInterface $sorter)
    {
        /* @var $sorterRule SorterRule */
        foreach ($sorter as $sorterRule) {
            $column = $sorterRule->getColumn();
            if (strpos($sorterRule->getColumn(), '.') === false && !$this->isColumnSelected($sorterRule->getColumn(), $qb)) {
                $column = $this->getPrefixedColumnWithRootEntityAlias($sorterRule->getColumn(), $qb);
            }

            $qb->addOrderBy($column, $sorterRule->getDirection());
        }
    }

    /**
     * @param SorterRule $sorterRule
     * @param QueryBuilder $qb
     * @return bool
     */
    private function isColumnSelected($column, QueryBuilder $qb)
    {
        $selectedColumns = [];
        $selectPart = implode(',', $qb->getDQLPart('select'));
        $chunks = explode(',', $selectPart);
        foreach ($chunks as $chunk) {
            $c = explode(' ', $chunk);
            $selectedColumns[] = end($c);
        }

        return in_array($column, $selectedColumns);
    }

    /**
     * @param string $column
     * @param QueryBuilder $qb
     * @return string
     */
    private function getPrefixedColumnWithRootEntityAlias($column, QueryBuilder $qb)
    {
        $aliases = $qb->getRootAliases();

        return sprintf('%s.%s', reset($aliases), $column);
    }
}
