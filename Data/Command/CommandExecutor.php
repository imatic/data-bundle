<?php
namespace Imatic\Bundle\DataBundle\Data\Command;

class CommandExecutor implements CommandExecutorInterface
{
    /**
     * @var HandlerRepositoryInterface
     */
    private $handlerRepository;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param HandlerRepositoryInterface $handlerRepository
     * @param bool                       $debug
     */
    public function __construct(HandlerRepositoryInterface $handlerRepository, $debug = false)
    {
        $this->handlerRepository = $handlerRepository;
        $this->debug = $debug;
    }

    /**
     * @param CommandInterface $command
     *
     * @throws \Exception
     *
     * @return CommandResultInterface
     */
    public function execute(CommandInterface $command)
    {
        $commandHandler = $this->handlerRepository->getHandler($command);

        if ($commandHandler instanceof CommandExecutorAwareInterface) {
            $commandHandler->setCommandExecutor($this);
        }

        try {
            $result = $commandHandler->handle($command);

            if (!($result instanceof CommandResultInterface)) {
                if (false === $result) {
                    $result = CommandResult::error();
                } else {
                    $result = CommandResult::success();
                }
            }
        } catch (\Exception $e) {
            if ($this->debug) {
                throw $e;
            }

            $result = CommandResult::error(null, [], $e);
        }

        $this->processMessages($command, $result);

        return $result;
    }

    /**
     * @param CommandInterface       $command
     * @param CommandResultInterface $result
     */
    private function processMessages(CommandInterface $command, CommandResultInterface $result)
    {
        $translationDomain = $this->handlerRepository->getBundleName($command) . 'Messages';

        foreach ($result->getMessages() as $message) {
            if (!$message->getTranslationDomain()) {
                $message->setTranslationDomain($translationDomain);
            }
            $message->setPrefix($command->getHandlerName());
        }
    }
}
