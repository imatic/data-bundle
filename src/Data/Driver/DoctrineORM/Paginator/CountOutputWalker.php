<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\AST\SelectStatement;
use Doctrine\ORM\Query\ParserResult;
use Doctrine\ORM\Tools\Pagination\CountOutputWalker as DoctrineWalker;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator\Impl\AstUtil;

class CountOutputWalker extends DoctrineWalker
{
    function __construct(
        Query $query, ParserResult $parserResult, private readonly array $queryComponents
    ) {
        parent::__construct($query, $parserResult, $queryComponents);
    }

    function walkSelectStatement(SelectStatement $ast): string
    {
        AstUtil::trim($ast, $this->queryComponents);

        return parent::walkSelectStatement($ast);
    }
}
