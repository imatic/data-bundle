<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command;

use Imatic\Bundle\DataBundle\Data\Command\Command;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandExecutorAwareTrait;
use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
use Imatic\Bundle\DataBundle\Data\Command\CommandResultInterface;
use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;

class BatchHandler implements HandlerInterface, CommandExecutorAwareInterface
{
    use CommandExecutorAwareTrait;

    private RecordIterator $recordIterator;
    private string $commandName;

    /**
     * @var mixed[]
     */
    private array $commandParameters;

    /**
     * @param mixed[] $commandParameters
     */
    public function __construct(RecordIterator $recordIterator, string $commandName, array $commandParameters = [])
    {
        $this->recordIterator = $recordIterator;
        $this->commandName = $commandName;
        $this->commandParameters = $commandParameters;
    }

    public function handle(CommandInterface $command): CommandResultInterface
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

    /**
     * @param mixed $item
     *
     * @return mixed[]
     */
    private function getCommandParameters(CommandInterface $command, $item): array
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
