<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

use Doctrine\ORM\QueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
interface RuleProcessorInterface
{
    /**
     * @param QueryBuilder $qb
     * @param FilterRule   $rule
     * @param string       $column
     */
    public function supports(FilterRule $rule, $column);

    /**
     * @param QueryBuilder $qb
     * @param FilterRule   $rule
     * @param string       $column
     */
    public function process(QueryBuilder $qb, FilterRule $rule, $column);
}
