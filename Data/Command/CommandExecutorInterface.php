<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandExecutorInterface
{
    public function execute(CommandInterface $command);
}
