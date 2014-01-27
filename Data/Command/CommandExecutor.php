<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

/*
 * todo: uzivatel - pokud je async zpracovani, musim umet predat uzivateli zpravy o zpracovani pokud bude treba
 */

class CommandExecutor implements CommandExecutorInterface
{
    /**
     * @var CommandHandlerRepositoryInterface
     */
    private $commandHandlerRepository;

    public function __construct(CommandHandlerRepositoryInterface $commandHandlerRepository)
    {
        $this->commandHandlerRepository = $commandHandlerRepository;
    }

    /**
     * @param CommandInterface $command
     * @return CommandResultInterface
     */
    public function execute(CommandInterface $command)
    {
        $commandHandler = $this->commandHandlerRepository->getCommandHandler($command);
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
