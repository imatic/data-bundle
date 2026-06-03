<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DefaultRuleProcessor extends AbstractFilterRuleProcessor
{
    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
        $type = $rule->getType() ?? (
            \is_array($rule->getValue())
                ? ArrayParameterType::STRING
                : ParameterType::STRING
        );
        $qb->setParameter($this->getQueryParameterName($rule), $rule->getValue(), $type);

        return $qb->expr()->{$rule->getOperator()}($column, $this->getQueryParameter($rule));
    }
}
