<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface PatchCommandHandlerInterface
{
    public function handle(PatchCommandInterface $pathCommand);
}
