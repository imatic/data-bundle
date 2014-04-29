<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class CallbackProcessor extends AbstractRuleProcessor
{
    /**
     * {@inheritdoc}
     */
    public function process(QueryBuilder $qb, FilterRule $rule, $column)
    {
         $column($qb, $rule);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(QueryBuilder $qb, FilterRule $rule, $column)
    {
        return $column instanceof \Closure;
    }
}
