<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface BatchCommandHandlerInterface extends CommandHandlerInterface
{
    public function handle(BatchCommandInterface $batchCommand);
}
