<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class PatchCommand extends Command implements PatchCommandInterface
{
    use ParametersAwareTrait;

    /**
     * @var array
     */
    private $objectIdentity;

    /**
     * @var array
     */
    private $parameters;

    public function __construct($commandHandlerName, $objectIdentity, array $parameters = array())
    {
        parent::__construct($commandHandlerName);

        $this->objectIdentity = $objectIdentity;
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getObjectIdentity()
    {
        return $this->objectIdentity;
    }
}