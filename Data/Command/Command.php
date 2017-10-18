<?php
namespace Imatic\Bundle\DataBundle\Data\Command;

use Imatic\Bundle\DataBundle\Exception\ParameterNotFoundException;

class Command implements CommandInterface
{
    /**
     * @var string
     */
    private $handlerName;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param string $handlerName Alias of the command handler service.
     * @param array  $parameters Parameters used by command handler.
     */
    public function __construct($handlerName, array $parameters = [])
    {
        $this->handlerName = $handlerName;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getHandlerName()
    {
        return $this->handlerName;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $name
     *
     * @throws ParameterNotFoundException
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        if (\array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }

        throw new ParameterNotFoundException($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasParameter($name)
    {
        return \array_key_exists($name, $this->parameters);
    }

    /**
     * String representation of object.
     *
     * @return string
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    /**
     * Constructs the object.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
}
