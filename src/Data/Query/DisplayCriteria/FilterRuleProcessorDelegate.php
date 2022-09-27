<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
class FilterRuleProcessorDelegate
{
    /**
     * @var FilterRuleProcessorInterface[]
     */
    private array $filterRuleProcessors = [];

    /**
     * @param mixed $column
     *
     * @throws \LogicException
     */
    public function process(object $qb, FilterRule $rule, $column): void
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
    public function setFilterRuleProcessors(array $filterRuleProcessors): void
    {
        $this->filterRuleProcessors = $filterRuleProcessors;
    }
}
