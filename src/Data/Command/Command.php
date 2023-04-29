<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

use Imatic\Bundle\DataBundle\Exception\ParameterNotFoundException;

class Command implements CommandInterface
{
    private string $handlerName;

    /**
     * @var mixed[]
     */
    private array $parameters;

    /**
     * @param string $handlerName Alias of the command handler service.
     * @param mixed[] $parameters Parameters used by command handler.
     */
    public function __construct(string $handlerName, array $parameters = [])
    {
        $this->handlerName = $handlerName;
        $this->parameters = $parameters;
    }

    public function getHandlerName(): string
    {
        return $this->handlerName;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter(string $name)
    {
        if (\array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }

        throw new ParameterNotFoundException($name);
    }

    public function hasParameter(string $name): bool
    {
        return \array_key_exists($name, $this->parameters);
    }

    public function __serialize(): array
    {
        return [];
    }

    public function __unserialize(array $data): void
    {
    }

    public function serialize(): ?string
    {
        return null;
    }

    public function unserialize($data): void
    {
    }
}
