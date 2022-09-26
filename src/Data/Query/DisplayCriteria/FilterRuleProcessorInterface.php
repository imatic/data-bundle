<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
interface FilterRuleProcessorInterface
{
    /**
     * @param mixed $column
     */
    public function supports(object $qb, FilterRule $rule, $column): bool;

    /**
     * @param mixed $column
     */
    public function process(object $qb, FilterRule $rule, $column): void;
}
