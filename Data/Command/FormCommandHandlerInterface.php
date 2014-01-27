<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface FormCommandHandlerInterface
{
    public function handle(FormCommandInterface $formCommand);
}
