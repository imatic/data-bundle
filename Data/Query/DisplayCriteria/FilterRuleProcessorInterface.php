<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
interface FilterRuleProcessorInterface
{
    /**
     * @param object          $qb
     * @param FilterRule      $rule
     * @param string|string[] $column
     *
     * @return bool
     */
    public function supports($qb, FilterRule $rule, $column);

    /**
     * @param object          $qb
     * @param FilterRule      $rule
     * @param string|string[] $column
     */
    public function process($qb, FilterRule $rule, $column);
}
