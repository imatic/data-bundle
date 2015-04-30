<?php

namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\RuleProcessor;

use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ContainsOperatorProcessor extends AbstractRuleProcessor
{
    private $postgresOperators = [
        FilterOperatorMap::OPERATOR_CONTAINS => 'ILIKE',
        FilterOperatorMap::OPERATOR_NOT_CONTAINS => 'NOT ILIKE',
    ];

    /**
     * {@inheritdoc}
     */
    public function process($qb, FilterRule $rule, $column)
    {
        if (!$this->hasPostgresqlConnection($qb)) {
            $qb->andWhere($qb->expr()->{$rule->getOperator()}($column, $this->getQueryParameter($rule)));
        } else {
            if ($qb instanceof ORMQueryBuilder) {
                $qb->andWhere(sprintf(
                    '%s(%s, %s) = true',
                    str_replace(' ', '_', $this->postgresOperators[$rule->getOperator()]),
                    $column,
                    $this->getQueryParameter($rule)
                ));
            } else {
                $qb->andWhere(sprintf(
                    '%s %s %s',
                    $column,
                    $this->postgresOperators[$rule->getOperator()],
                    $this->getQueryParameter($rule)
                ));
            }
        }

        $qb->setParameter($this->getQueryParameterName($rule), '%' . $rule->getValue() . '%', $rule->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterRule $rule, $column)
    {
        return in_array($rule->getOperator(), [
            FilterOperatorMap::OPERATOR_CONTAINS,
            FilterOperatorMap::OPERATOR_NOT_CONTAINS
        ]);
    }

    private function hasPostgresqlConnection($qb)
    {
        $connection = null;
        if ($qb instanceof DBALQueryBuilder) {
            $connection = $qb->getConnection();
        } elseif ($qb instanceof ORMQueryBuilder) {
            $connection = $qb->getEntityManager()->getConnection();
        } else {
            throw new \RuntimeException('Cannot retrieve db connection.');
        }

        return $connection->getDatabasePlatform()->getName() === 'postgresql';
    }
}
