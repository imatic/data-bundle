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
        if ($commandHandler instanceof CommandExecutorAwareInterface) {
            $commandHandler->setCommandExecutor($this);
        }

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
            if ($this->debug) {
                throw $e;
            }

            $result = new CommandResult(false, [], $e);
        }

        $this->processMessages($command, $result);

        return $result;
    }

    /**
     * todo: mela by resit post-execute event
     *
     * @param CommandInterface $command
     * @param CommandResultInterface $result
     */
    private function processMessages(CommandInterface $command, CommandResultInterface $result)
    {
        $translationDomain = $this->handlerRepository->getBundleName($command) . 'Messages';

        if ($result->hasMessages()) {
            foreach ($result->getMessages() as $message) {
                if (!$message->getTranslationDomain()) {
                    $message->setTranslationDomain($translationDomain);
                }
                $message->setPrefix($command->getHandlerName());
            }
        } else {
            $type = $result->isSuccessful() ? 'success' : 'error';
            $message = new Message($type, $type, [], $translationDomain);
            $message->setPrefix($command->getHandlerName());
            $result->addMessage($message);
        }
    }
}