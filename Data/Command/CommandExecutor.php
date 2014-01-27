<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

/*
 * todo
 * zpravy
 * - vypis pro uzivatele jak sync tak async
 *
 * vyjimky
 * - bude se zobrazovat text vyjimky uzivateli, nebo vse pojede pres zpravy?
 * - jak chytat a zpracovavat vyjimky?
 *  - musim poznat kdy je vyjimka napr nenalezeni dat (async message vyrizena???) a kdy nastala chyba (async message nevyrizena)
 *
 * uzivatel
 * - pokud je async zpracovani, musim umet predat uzivateli zpravy o zpracovani pokud bude treba
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

    public function execute(CommandInterface $command)
    {
        $commandHandler = $this->commandHandlerRepository->getCommandHandler($command);
        $commandHandler->handle($command);
    }
}
