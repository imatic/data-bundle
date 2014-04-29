<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use LogicException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RuleProcessor
{
    /** @var RuleProcessorInterface[] */
    private $ruleProcessors = [];

    /**
     * @param QueryBuilder $qb
     * @param FilterRule   $rule
     * @param type         $column
     *
     * @throws LogicException
     */
    public function process(QueryBuilder $qb, FilterRule $rule, $column)
    {
        foreach ($this->ruleProcessors as $ruleProcessor) {
            if ($ruleProcessor->supports($qb, $rule, $column)) {
                $ruleProcessor->process($qb, $rule, $column);

                return;
            }
        }

        throw new LogicException(sprintf('Couldn\'t find any rule processor suitable to rule "%s" and column "%s"', $rule->getName(), $column));
    }

    /**
     * @param RuleProcessorInterface $ruleProcessor
     */
    public function addRuleProcessor(RuleProcessorInterface $ruleProcessor)
    {
        $this->ruleProcessors[] = $ruleProcessor;
    }
}
