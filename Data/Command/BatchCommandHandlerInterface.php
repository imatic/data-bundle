<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface BatchCommandHandlerInterface
{
    public function handle(BatchCommandInterface $batchCommand);
}
