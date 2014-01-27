<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandRepositoryInterface
{
    public function getCommand($name);
}
