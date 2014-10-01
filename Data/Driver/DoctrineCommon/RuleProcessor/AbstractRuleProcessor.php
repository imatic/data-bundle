<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\RuleProcessor;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
abstract class AbstractRuleProcessor implements RuleProcessorInterface
{
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
