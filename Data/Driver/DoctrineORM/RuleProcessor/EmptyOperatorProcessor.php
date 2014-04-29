<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class EmptyOperatorProcessor extends AbstractRuleProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process(QueryBuilder $qb, FilterRule $rule, $column)
    {
        $qb->andWhere($qb->expr()->{$rule->getOperator()}($column));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(QueryBuilder $qb, FilterRule $rule, $column)
    {
        return in_array($rule->getOperator(), [
            FilterOperatorMap::OPERATOR_EMPTY,
            FilterOperatorMap::OPERATOR_NOT_EMPTY,
        ]);
    }
}
