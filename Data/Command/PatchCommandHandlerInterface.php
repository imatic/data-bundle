<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface PatchCommandHandlerInterface extends CommandHandlerInterface
{
    public function handle(PatchCommandInterface $pathCommand);
}
