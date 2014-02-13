<?php

namespace Imatic\Bundle\DataBundle\Data\Command\Factory;

interface CommandFactoryInterface
{
    /**
     * @param  string           $name
     * @param  string           $handlerName
     * @param  array            $parameters
     * @return CommandInterface
     */
    public function createCommand($name, $handlerName, array $parameters);
}