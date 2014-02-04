<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class CommandExecutor implements CommandExecutorInterface
{
    /**
     * @var HandlerRepositoryInterface
     */
    private $handlerRepository;

    /**
     * @param HandlerRepositoryInterface $handlerRepository
     */
    public function __construct(HandlerRepositoryInterface $handlerRepository)
    {
        $this->handlerRepository = $handlerRepository;
    }

    /**
     * @param  CommandInterface       $command
     * @return CommandResultInterface
     */
    public function execute(CommandInterface $command)
    {
        $commandHandler = $this->handlerRepository->getHandler($command);
        try {
            $result = $commandHandler->handle($command);

            if (!($result instanceof CommandResultInterface)) {
                if (false === $result) {
                    $result = new CommandResult(false);
                } else {
                    $result = new CommandResult(true);
                }
            }
        } catch (\Exception $e) {
            $result = new CommandResult(false, [], $e);
        }

        return $result;
    }
}
