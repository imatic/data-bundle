<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\RuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DefaultRuleProcessor extends AbstractRuleProcessor
{
    /**
     * {@inheritdoc}
     */
    public function supports(FilterRule $rule, $column)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function process($qb, FilterRule $rule, $column)
    {
        $qb->andWhere($qb->expr()->{$rule->getOperator()}($column, $this->getQueryParameter($rule)));
        $qb->setParameter($this->getQueryParameterName($rule), $rule->getValue());
    }
}