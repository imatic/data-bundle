<?php

namespace Imatic\Bundle\DataBundle\Data\Command;

class FormCommand extends Command implements FormCommandInterface
{
    use ParametersAwareTrait;

    /**
     * @var array
     */
    private $objectIdentity;

    /**
     * @var array|object
     */
    private $data;

    /**
     * @var array
     */
    private $parameters;

    public function __construct($commandHandlerName, array $objectIdentity, $data, array $parameters = array())
    {
        parent::__construct($commandHandlerName);

        $this->objectIdentity = $objectIdentity;
        $this->parameters = $parameters;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getObjectIdentity()
    {
        return $this->objectIdentity;
    }

    /**
     * @return array|object
     */
    public function getData()
    {
        return $this->data;
    }
}