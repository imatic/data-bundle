<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
class FilterRuleProcessorDelegate
{
    /** @var FilterRuleProcessorInterface[] */
    private $filterRuleProcessors = [];

    /**
     * @param object     $qb
     * @param FilterRule $rule
     * @param string     $column
     *
     * @throws \LogicException
     */
    public function process($qb, FilterRule $rule, $column)
    {
        foreach ($this->filterRuleProcessors as $filterRoleProcessor) {
            if ($filterRoleProcessor->supports($qb, $rule, $column)) {
                $filterRoleProcessor->process($qb, $rule, $column);

                return;
            }
        }

        throw new \LogicException(\sprintf(
            'Couldn\'t find any filter rule processor suitable to filter rule "%s" and column "%s"',
            $rule->getName(),
            $column
        ));
    }

    /**
     * @param FilterRuleProcessorInterface[] $filterRuleProcessors
     */
    public function setFilterRuleProcessors(array $filterRuleProcessors)
    {
        $this->filterRuleProcessors = $filterRuleProcessors;
    }
}
