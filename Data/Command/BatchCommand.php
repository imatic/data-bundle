<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class BatchCommand extends Command implements BatchCommandInterface
{
    use ParametersAwareTrait;

    /**
     * @var array
     */
    private $objectIdentities;

    /**
     * @var array
     */
    private $parameters;

    public function __construct($commandHandlerName, array $objectIdentities, array $parameters = array())
    {

        parent::__construct($commandHandlerName);

        $this->objectIdentities = $objectIdentities;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectIdentities()
    {
        return $this->objectIdentities;
    }
}