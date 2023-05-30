<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator\Impl;

use Doctrine\ORM\Query\AST\ArithmeticExpression;
use Doctrine\ORM\Query\AST\ComparisonExpression;
use Doctrine\ORM\Query\AST\ConditionalExpression;
use Doctrine\ORM\Query\AST\ConditionalPrimary;
use Doctrine\ORM\Query\AST\ConditionalTerm;
use Doctrine\ORM\Query\AST\InExpression;
use Doctrine\ORM\Query\AST\InListExpression;
use Doctrine\ORM\Query\AST\InputParameter;
use Doctrine\ORM\Query\AST\InSubselectExpression;
use Doctrine\ORM\Query\AST\Literal;
use Doctrine\ORM\Query\AST\NullComparisonExpression;
use Doctrine\ORM\Query\AST\PathExpression;
use Imatic\Bundle\DataBundle\Doctrine\Postgresql\Query\AST\ILike;
use Imatic\Bundle\DataBundle\Doctrine\Postgresql\Query\AST\NotILike;
use LogicException;

class RequiredAliasConditionParser
{
    /**
     * @var ?\Closure[]
     */
    private static ?array $handlers = null;

    /**
     * @return \Closure[]
     */
    private static function create(): array
    {
        return  [
            ConditionalTerm::class => function (ConditionalTerm $expr) {
                return \call_user_func_array(
                    'array_merge',
                    \array_map(
                        function ($expr) {
                            return self::parse($expr);
                        },
                        $expr->conditionalFactors
                    )
                );
            },
            ConditionalPrimary::class => function (ConditionalPrimary $expr): array {
                return self::parse($expr->simpleConditionalExpression ?? $expr->conditionalExpression);
            },
            ConditionalExpression::class => function (ConditionalExpression $expr) {
                return \call_user_func_array(
                    'array_merge',
                    \array_map(
                        function ($expr) {
                            return self::parse($expr);
                        },
                        $expr->conditionalTerms
                    )
                );
            },
            ComparisonExpression::class => function (ComparisonExpression $expr): array {
                return \array_merge(
                    self::parse($expr->leftExpression),
                    self::parse($expr->rightExpression)
                );
            },
            InExpression::class => function (InExpression $expr): array {
                return self::parse($expr->expression->simpleArithmeticExpression);
            },
            InListExpression::class => function (InListExpression $expr): array {
                return self::parse($expr->expression->simpleArithmeticExpression);
            },
            InSubselectExpression::class => function (InSubselectExpression $expr): array {
                return self::parse($expr->expression->simpleArithmeticExpression);
            },
            PathExpression::class => function (PathExpression $expr): array {
                return [$expr->identificationVariable];
            },
            ArithmeticExpression::class => function (ArithmeticExpression $expr): array {
                return self::parse($expr->simpleArithmeticExpression);
            },
            ILike::class => function (ILike $expr) {
                return \array_merge(
                    self::parse($expr->field),
                    self::parse($expr->value),
                );
            },
            NotILike::class => function (NotILike $expr): array {
                return \array_merge(
                    self::parse($expr->field),
                    self::parse($expr->value),
                );
            },
            NullComparisonExpression::class => function (NullComparisonExpression $expr) {
                return self::parse($expr->expression);
            },
            InputParameter::class => function (InputParameter $expr): array {
                return [];
            },
            Literal::class => function (Literal $expr): array {
                return [];
            },
        ];
    }

    /**
     * @return mixed
     */
    public static function parse(object $expr)
    {
        if (!self::$handlers) {
            self::$handlers = self::create();
        }

        $handler = self::$handlers[\get_class($expr)] ?? null;

        if (!$handler) {
            throw new LogicException(\sprintf(
                'Handler for "%s" expr is not registered.',
                \get_class($expr)
            ));
        }

        return $handler($expr);
    }
}
