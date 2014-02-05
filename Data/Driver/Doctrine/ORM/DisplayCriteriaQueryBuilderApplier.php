<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderApplierInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DisplayCriteriaQueryBuilderApplier implements DisplayCriteriaQueryBuilderApplierInterface
{
    /**
     * @var array [isColumnAggregated][conditionType] which returns method on queryBuilder to call
     */
    private $filterAggregatedMap = [
        true => [
            FilterRule::CONDITION_AND => 'andHaving',
            FilterRule::CONDITION_OR => 'orHaving',
        ],
        false => [
            FilterRule::CONDITION_AND => 'andWhere',
            FilterRule::CONDITION_OR => 'orWhere',
        ],
    ];

    /**
     * @param QueryBuilder             $qb
     * @param DisplayCriteriaInterface $displayCriteria
     */
    public function apply($qb, DisplayCriteriaInterface $displayCriteria = null)
    {
        if ($displayCriteria === null) {
            return;
        }

        $this->applyPager($qb, $displayCriteria->getPager());
        $this->applyFilter($qb, $displayCriteria->getFilter());
        $this->applySorter($qb, $displayCriteria->getSorter());
    }

    /**
     * @param QueryBuilder   $qb
     * @param PagerInterface $pager
     */
    private function applyPager(QueryBuilder $qb, PagerInterface $pager)
    {
        $qb
            ->setFirstResult($pager->getOffset())
            ->setMaxResults($pager->getLimit())
        ;
    }

    /**
     * @param QueryBuilder    $qb
     * @param FilterInterface $filter
     */
    private function applyFilter(QueryBuilder $qb, FilterInterface $filter)
    {
        /* @var $filterRule FilterRule */
        foreach ($filter as $filterRule) {
            $parameterName = str_replace('.', '_', $filterRule->getColumn());

            $dqlPart = sprintf('%s %s %s',
                $this->getColumnDqlPart($filterRule, $qb),
                $filterRule->getOperator(),
                $this->getParameterDqlPart($parameterName, $filterRule->getValue())
            );

            $qbMethod = $this->filterAggregatedMap[$filterRule->isAggregated()][$filterRule->getCondition()];
            $qb->$qbMethod($dqlPart);
            $qb->setParameter($parameterName, $filterRule->getValue());
        }
    }

    /**
     * @param FilterRule   $filterRule
     * @param QueryBuilder $qb
     *
     * @return string
     */
    private function getColumnDqlPart(FilterRule $filterRule, QueryBuilder $qb)
    {
        if (!$filterRule->isAggregated() && strpos($filterRule->getColumn(), '.') === false) {
            $aliases = $qb->getRootAliases();

            return sprintf('%s.%s', reset($aliases), $filterRule->getColumn());
        }

        return $filterRule->getColumn();
    }

    /**
     * @param string $parameterName
     * @param mixed  $filterValue
     *
     * @return string
     */
    private function getParameterDqlPart($parameterName, $filterValue)
    {
        $parameter = ':' . $parameterName;

        return is_array($filterValue) ? sprintf('(%s)', $parameter) : $parameter;
    }

    /**
     * @param QueryBuilder    $qb
     * @param SorterInterface $sorter
     */
    private function applySorter(QueryBuilder $qb, SorterInterface $sorter)
    {
        /* @var $sorterRule SorterRule */
        foreach ($sorter as $sorterRule) {
            $qb->addOrderBy($sorterRule->getColumn(), $sorterRule->getDirection());
        }
    }
}
