<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRuleProcessorInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
abstract class AbstractFilterRuleProcessor implements FilterRuleProcessorInterface
{
    public function supports($qb, FilterRule $rule, $column)
    {
        return $qb instanceof ORMQueryBuilder || $qb instanceof DBALQueryBuilder;
    }

    public function process($qb, FilterRule $rule, $column)
    {
        $fixedColumns = is_array($column) ? $column : [$column];

        $exprs = [];
        foreach ($fixedColumns as $oneColumn) {
            $exprs[] = $this->processOneColumn($qb, $rule, $oneColumn);
        }

        $qb->andWhere(call_user_func_array([$qb->expr(), 'orX'], $exprs));
    }

    abstract protected function processOneColumn($qb, FilterRule $rule, $column);

    /**
     * @param FilterRule $rule
     *
     * @return string
     */
    protected function getQueryParameter(FilterRule $rule)
    {
        return sprintf(
            $rule->getOption('query_parameter_format'),
            ':' . $this->getQueryParameterName($rule)
        );
    }

    /**
     * @param FilterRule $rule
     *
     * @return string
     */
    protected function getQueryParameterName(FilterRule $rule)
    {
        return $rule->getName();
    }
}
