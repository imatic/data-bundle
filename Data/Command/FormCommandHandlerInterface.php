<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface FormCommandHandlerInterface extends CommandHandlerInterface
{
    public function handle(FormCommandInterface $formCommand);
}
