<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface;
use InvalidArgumentException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RecordIteratorArgs
{
    protected CommandInterface $command;
    protected QueryObjectInterface $queryObject;
    protected int $pageLimit = 100;

    /**
     * @var callable
     */
    protected $callback;

    public function __construct(CommandInterface $command, QueryObjectInterface $queryObject, callable $callback)
    {
        if (!$queryObject instanceof FilterableQueryObjectInterface) {
            throw new InvalidArgumentException(\sprintf(
                '%s have to be instance of "%s"',
                \get_class($queryObject),
                'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface'
            ));
        }

        if (!$queryObject instanceof SelectableQueryObjectInterface) {
            throw new InvalidArgumentException(\sprintf(
                '%s have to be instance of "%s"',
                \get_class($queryObject),
                'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface'
            ));
        }

        $this->command = $command;
        $this->queryObject = $queryObject;
        $this->callback = $callback;
    }

    public function getCommand(): CommandInterface
    {
        return $this->command;
    }

    public function getQueryObject(): QueryObjectInterface
    {
        return $this->queryObject;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function setPageLimit(int $limit): void
    {
        $this->pageLimit = $limit;
    }

    public function getPageLimit(): int
    {
        return $this->pageLimit;
    }
}
