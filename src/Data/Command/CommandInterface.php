<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

use Imatic\Bundle\DataBundle\Exception\ParameterNotFoundException;

interface CommandInterface extends \Serializable
{
    /**
     * @return string the alias of a command handler service
     */
    public function getHandlerName(): string;

    /**
     * @return mixed[]
     */
    public function getParameters(): array;

    /**
     * @return mixed
     *
     * @throws ParameterNotFoundException
     */
    public function getParameter(string $name);

    public function hasParameter(string $name): bool;
}
