<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareTrait;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;

class BatchHandler implements HandlerInterface, CommandExecutorAwareInterface
{
    use CommandExecutorAwareTrait;

    /**
     * @var RecordIterator
     */
    private $recordIterator;

    /**
     * @var string
     */
    private $commandName;

    /**
     * @var array
     */
    private $commandParameters;

    public function __construct(RecordIterator $recordIterator, $commandName, array $commandParameters = [])
    {
        $this->recordIterator = $recordIterator;
        $this->commandName = $commandName;
        $this->commandParameters = $commandParameters;
    }

    public function handle(CommandInterface $command)
    {
        $callback = function ($item) use ($command) {
            $batchCommand = new Command(
                $this->commandName,
                $this->getCommandParameters($command, $item)
            );

            return $this->commandExecutor->execute($batchCommand);
        };

        $query = $command->getParameter('batch_query');
        $recordIteratorArgs = new RecordIteratorArgs($command, $query, $callback);

        return $this->recordIterator->each($recordIteratorArgs);
    }

    private function getCommandParameters(CommandInterface $command, $item)
    {
        $parameters = $command->hasParameter('batch_command_parameters')
            ? $command->getParameter('batch_command_parameters')
            : [];

        $parameters = \array_replace($this->commandParameters, $parameters);
        $parameters['data'] = $item;

        if ($command->hasParameter('batch_command_parameters_callback')) {
            $parameters = \call_user_func($command->getParameter('batch_command_parameters_callback'), $parameters);
        }

        return $parameters;
    }
}
