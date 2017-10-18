<?php
namespace Imatic\Bundle\DataBundle\Data\Command;

use Imatic\Bundle\DataBundle\Exception\ParameterNotFoundException;

interface CommandInterface extends \Serializable
{
    /**
     * @return string the alias of a command handler service
     */
    public function getHandlerName();

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param string $name
     *
     * @throws ParameterNotFoundException
     *
     * @return mixed
     */
    public function getParameter($name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasParameter($name);
}
