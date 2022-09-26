<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

use Psr\Log\LoggerInterface;

class CommandExecutor implements CommandExecutorInterface
{
    private HandlerRepositoryInterface $handlerRepository;
    private ?LoggerInterface $logger;
    private bool $debug;

    public function __construct(HandlerRepositoryInterface $handlerRepository, LoggerInterface $logger = null, bool $debug = false)
    {
        $this->handlerRepository = $handlerRepository;
        $this->logger = $logger;
        $this->debug = $debug;
    }

    /**
     * @throws \Exception
     */
    public function execute(CommandInterface $command): CommandResultInterface
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

            if (null !== $this->logger) {
                $this->logger->error('An exception was thrown when handling an Command.', ['exception' => $e]);
            }

            $result = CommandResult::error('error', [], $e);
        }

        $this->processMessages($command, $result);

        return $result;
    }

    private function processMessages(CommandInterface $command, CommandResultInterface $result): void
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
