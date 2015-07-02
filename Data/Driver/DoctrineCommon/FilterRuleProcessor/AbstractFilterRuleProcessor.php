<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
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
