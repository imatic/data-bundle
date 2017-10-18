<?php
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command;

use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SelectableQueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RecordIteratorArgs
{
    /**
     * @var CommandInterface
     */
    protected $command;

    /**
     * @var QueryObjectInterface
     */
    protected $queryObject;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var int
     */
    protected $pageLimit = 100;

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

    /**
     * @return CommandInterface
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return QueryObjectInterface
     */
    public function getQueryObject()
    {
        return $this->queryObject;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param int $limit
     */
    public function setPageLimit($limit)
    {
        $this->pageLimit = $limit;
    }

    /**
     * @return int
     */
    public function getPageLimit()
    {
        return $this->pageLimit;
    }
}
