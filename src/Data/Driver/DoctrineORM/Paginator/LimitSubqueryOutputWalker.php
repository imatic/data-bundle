<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator;

use Doctrine\ORM\Query\AST\SelectStatement;
use Doctrine\ORM\Tools\Pagination\LimitSubqueryOutputWalker as DoctrineWalker;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator\Impl\AstUtil;

class LimitSubqueryOutputWalker extends DoctrineWalker
{
    /**
     * @var mixed[]
     */
    private array $queryComponents;

    /**
     * @param mixed[] $queryComponents
     */
    public function __construct($query, $parserResult, array $queryComponents)
    {
        $this->queryComponents = $queryComponents;

        parent::__construct($query, $parserResult, $queryComponents);
    }

    public function walkSelectStatementWithRowNumber(SelectStatement $AST): string
    {
        AstUtil::trim($AST, $this->queryComponents);

        return parent::walkSelectStatementWithRowNumber($AST);
    }
}
