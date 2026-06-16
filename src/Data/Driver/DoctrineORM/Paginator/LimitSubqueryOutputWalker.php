<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\AST\SelectStatement;
use Doctrine\ORM\Query\ParserResult;
use Doctrine\ORM\Tools\Pagination\LimitSubqueryOutputWalker as DoctrineWalker;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator\Impl\AstUtil;

class LimitSubqueryOutputWalker extends DoctrineWalker
{
    public function __construct(
        Query $query,
        ParserResult $parserResult,
        private readonly array $queryComponents
    ) {
        parent::__construct($query, $parserResult, $queryComponents);
    }

    public function walkSelectStatementWithRowNumber(SelectStatement $AST): string
    {
        AstUtil::trim($AST, $this->queryComponents);

        return parent::walkSelectStatementWithRowNumber($AST);
    }
}
