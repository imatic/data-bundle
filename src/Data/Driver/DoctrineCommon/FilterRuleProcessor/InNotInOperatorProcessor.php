<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

class InNotInOperatorProcessor extends AbstractFilterRuleProcessor
{
    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
        $qb->setParameter($this->getQueryParameterName($rule), $rule->getValue(), $this->getType($rule));

        return $qb->expr()->{$rule->getOperator()}($column, $this->getQueryParameter($rule));
    }

    public function supports(object $qb, FilterRule $rule, $column): bool
    {
        return parent::supports($qb, $rule, $column) &&
            \in_array($rule->getOperator(), [FilterOperatorMap::OPERATOR_IN, FilterOperatorMap::OPERATOR_NOT_IN], true);
    }

    private function getType(FilterRule $rule): ParameterType|ArrayParameterType|string
    {
        $value = $rule->getValue();
        if ($rule->getType() || !\is_array($value)) {
            return $rule->getType() ?? ParameterType::STRING;
        }

        return \count(\array_filter($value, 'is_numeric')) === \count($value)
            ? ArrayParameterType::INTEGER
            : ArrayParameterType::STRING;
    }
}
