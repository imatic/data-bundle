<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator\Impl;

use Doctrine\ORM\Query\AST\Join;
use Doctrine\ORM\Query\AST\OrderByItem;
use Doctrine\ORM\Query\AST\PathExpression;
use Doctrine\ORM\Query\AST\SelectExpression;
use Doctrine\ORM\Query\AST\SelectStatement;
use LogicException;
use RuntimeException;

class AstUtil
{
    private static function trimSelect(SelectStatement $ast): void
    {
        $from = $ast->fromClause->identificationVariableDeclarations;
        if (\count($from) !== 1) {
            throw new RuntimeException('Cannot count query which selects two FROM components, cannot make distinction');
        }
        $fromRoot = \reset($from);
        $rootAlias = $fromRoot->rangeVariableDeclaration->aliasIdentificationVariable;

        $ast->selectClause->selectExpressions = \array_filter(
            $ast->selectClause->selectExpressions,
            function (SelectExpression $expr) use ($rootAlias) {
                return $expr->expression === $rootAlias;
            }
        );
    }

    private static function orderByRequiredAliases(SelectStatement $ast): array
    {
        return \array_map(
            function (OrderByItem $obi) {
                if (!$obi->expression instanceof PathExpression) {
                    throw new LogicException(
                        'Expected expression of type "%s", got "%s".',
                        \gettype($obi->expression) === 'object' ? \get_class($obi->expression) : \gettype($obi->expression)
                    );
                }

                return $obi->expression->identificationVariable;
            },
            $ast->orderByClause->orderByItems ?? []
        );
    }

    private static function whereRequiredAliases(SelectStatement $ast): array
    {
        if (!$ast->whereClause) {
            return [];
        }

        return RequiredAliasConditionParser::parse($ast->whereClause->conditionalExpression);
    }

    private static function joinRequiredAliases(SelectStatement $ast, array $queryComponents): array
    {
        $requiredAliases = [];
        foreach ($ast->fromClause->identificationVariableDeclarations as $ivd) {
            foreach ($ivd->joins as $join) {
                if ($join->joinType !== Join::JOIN_TYPE_INNER) {
                    continue;
                }

                $joinColumns = $queryComponents[$join->joinAssociationDeclaration->aliasIdentificationVariable]['relation']['joinColumns'];
                if (\count($joinColumns) !== 1) {
                    throw new LogicException(\sprintf('Unsupported number of cols: %d', \count($joinColumns)));
                }

                if (!$joinColumns[0]['nullable']) {
                    continue;
                }

                $requiredAliases[] = $join->joinAssociationDeclaration->aliasIdentificationVariable;
            }
        }

        return $requiredAliases;
    }

    /**
     * @todo Extract dependencies from join condition
     */
    private static function joinDependencies(SelectStatement $ast): array
    {
        $joinDependencies = [];
        foreach ($ast->fromClause->identificationVariableDeclarations as $ivd) {
            foreach ($ivd->joins as $join) {
                $jad = $join->joinAssociationDeclaration;
                $joinDependencies[$jad->aliasIdentificationVariable] = $jad->joinAssociationPathExpression->identificationVariable;
            }
        }

        return $joinDependencies;
    }

    private static function trimJoins(SelectStatement $ast, array $requiredAliases): void
    {
        $rq = \array_flip($requiredAliases);
        foreach ($ast->fromClause->identificationVariableDeclarations as $ivd) {
            $ivd->joins = \array_filter(
                $ivd->joins,
                function (Join $join) use ($rq) {
                    return isset($rq[$join->joinAssociationDeclaration->aliasIdentificationVariable]);
                }
            );
        }
    }

    private static function requiredAliases(SelectStatement $ast, array $queryComponents): array
    {
        $requiredAliases = \array_flip(
            \array_merge(
                self::orderByRequiredAliases($ast),
                self::whereRequiredAliases($ast),
                self::joinRequiredAliases($ast, $queryComponents)
            )
        );

        $joinDependencies = self::joinDependencies($ast);

        foreach (\array_keys($requiredAliases) as $requiredAlias) {
            while (true) {
                if (!isset($joinDependencies[$requiredAlias])) {
                    break;
                }

                $requiredAlias = $joinDependencies[$requiredAlias];
                if (isset($requiredAliases[$requiredAlias])) {
                    break;
                }

                $requiredAliases[$requiredAlias] = true;
            }
        }

        return \array_keys($requiredAliases);
    }

    public static function trim(SelectStatement $ast, array $queryComponents)
    {
        self::trimSelect($ast);
        self::trimJoins($ast, self::requiredAliases($ast, $queryComponents));
    }
}
