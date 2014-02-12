<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class CommandExecutor implements CommandExecutorInterface
{
    /**
     * @var HandlerRepositoryInterface
     */
    private $handlerRepository;

    /**
     * @var boolean
     */
    private $debug;

    /**
     * @param HandlerRepositoryInterface $handlerRepository
     * @param bool $debug
     */
    public function __construct(HandlerRepositoryInterface $handlerRepository, $debug = false)
    {
        $this->handlerRepository = $handlerRepository;
        $this->debug = $debug;
    }

    /**
     * @param  CommandInterface $command
     * @throws \Exception
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
            if (!$this->debug) {
                throw $e;
            }

            $result = new CommandResult(false, [], $e);
        }

        return $result;
    }
}
