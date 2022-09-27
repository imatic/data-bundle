<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator;

use Doctrine\ORM\Query\AST\SelectStatement;
use Doctrine\ORM\Tools\Pagination\CountOutputWalker as DoctrineWalker;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator\Impl\AstUtil;

class CountOutputWalker extends DoctrineWalker
{
    /**
     * @var mixed[]
     */
    private array $queryComponents;

    public function __construct($query, $parserResult, array $queryComponents)
    {
        $this->queryComponents = $queryComponents;
        parent::__construct($query, $parserResult, $queryComponents);
    }

    public function walkSelectStatement(SelectStatement $ast): string
    {
        AstUtil::trim($ast, $this->queryComponents);

        return parent::walkSelectStatement($ast);
    }
}
