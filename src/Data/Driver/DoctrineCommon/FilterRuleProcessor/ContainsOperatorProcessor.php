<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ContainsOperatorProcessor extends AbstractFilterRuleProcessor
{
    private string $dbalFunctionTemplate;
    private string $ormFunctionTemplate;

    /**
     * @var array<string,string>
     */
    private array $postgresOperators = [
        FilterOperatorMap::OPERATOR_CONTAINS => 'ILIKE',
        FilterOperatorMap::OPERATOR_NOT_CONTAINS => 'NOT ILIKE',
    ];

    public function __construct()
    {
        $this->setFunction();
    }

    public function setFunction(string $function = null): void
    {
        $this->dbalFunctionTemplate = $function ? \sprintf('%s(%%s)', $function) : '%s';
        $this->ormFunctionTemplate = $function ? 'unaccent_lower(%s)' : '%s';
    }

    protected function processOneColumn($qb, FilterRule $rule, $column)
    {
        $qb->setParameter($this->getQueryParameterName($rule), '%' . $rule->getValue() . '%', $rule->getType());

        if (!$this->hasPostgresqlConnection($qb)) {
            return $qb->expr()->{$rule->getOperator()}(
                $this->wrapColumn($qb, $column),
                $this->wrapColumn($qb, $this->getQueryParameter($rule))
            );
        }

        if ($qb instanceof ORMQueryBuilder) {
            return \sprintf(
                '%s(%s, %s) = true',
                \str_replace(' ', '_', $this->postgresOperators[$rule->getOperator()]),
                $this->wrapColumn($qb, $column),
                $this->wrapColumn($qb, $this->getQueryParameter($rule))
            );
        }
        return \sprintf(
            '%s %s %s',
            $this->wrapColumn($qb, $column),
            $this->postgresOperators[$rule->getOperator()],
            $this->wrapColumn($qb, $this->getQueryParameter($rule))
        );
    }

    public function supports(object $qb, FilterRule $rule, $column): bool
    {
        return
            parent::supports($qb, $rule, $column)
            && \in_array($rule->getOperator(), [
                FilterOperatorMap::OPERATOR_CONTAINS,
                FilterOperatorMap::OPERATOR_NOT_CONTAINS,
            ], true);
    }

    /**
     * @param mixed $column
     */
    private function wrapColumn(object $qb, $column): string
    {
        $template = $qb instanceof ORMQueryBuilder ? $this->ormFunctionTemplate : $this->dbalFunctionTemplate;

        return \sprintf($template, $column);
    }

    private function hasPostgresqlConnection(object $qb): bool
    {
        if ($qb instanceof DBALQueryBuilder) {
            $connection = $qb->getConnection();
        } elseif ($qb instanceof ORMQueryBuilder) {
            $connection = $qb->getEntityManager()->getConnection();
        } else {
            throw new \RuntimeException('Cannot retrieve db connection.');
        }

        return $connection->getDatabasePlatform() instanceof PostgreSQLPlatform;
    }
}
