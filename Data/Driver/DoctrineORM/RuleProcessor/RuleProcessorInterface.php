<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor;

use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
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
     * @param ORMQueryBuilder|DBALQueryBuilder $qb
     * @param FilterRule   $rule
     * @param string       $column
     */
    public function process($qb, FilterRule $rule, $column);
}
