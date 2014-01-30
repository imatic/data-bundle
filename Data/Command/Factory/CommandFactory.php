<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class CommandFactory implements CommandFactoryInterface
{
    private $factories;

    public function __construct()
    {
        $this->factories = [
            'patch' => function (array $parameters) {

                },
            'batch' => function (array $parameters) {

                },
            'form' => function (array $parameters) {

                }
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createCommand($name, $handlerName, array $parameters)
    {
        if (array_key_exists($name, $this->factories)) {
            return new Command($handlerName, $this->factories[$name]);
        }
        // ex
    }
}