<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

final class CommandResult implements CommandResultInterface
{
    private bool $success;

    /**
     * @var mixed[]
     */
    private array $data;

    /**
     * @var MessageInterface[]
     */
    private array $messages;

    private ?\Exception $exception;

    /**
     * @param MessageInterface[] $messages
     *
     * @throws \LogicException
     */
    public function __construct(bool $success, array $messages = [], \Exception $exception = null)
    {
        if ($success && $exception) {
            throw new \LogicException('Result cannot be successful with exception.');
        }

        $this->success = $success;
        $this->messages = $messages;
        $this->exception = $exception;
        $this->data = [];
    }

    /**
     * @param mixed[] $parameters
     */
    public static function success(string $message = null, array $parameters = []): self
    {
        $messages = [];
        if ($message) {
            $messages[] = new Message('success', $message, $parameters);
        }

        return new self(true, $messages);
    }

    /**
     * @param mixed[] $parameters
     */
    public static function error(string $message = null, array $parameters = [], \Exception $exception = null): self
    {
        $messages = [];

        if ($message) {
            $messages[] = new Message('error', $message, $parameters);
        }

        return new self(false, $messages, $exception);
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }

    public function hasMessages(): bool
    {
        return !empty($this->messages);
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getMessagesAsString(): string
    {
        return \implode(', ', $this->messages);
    }

    public function addMessage(MessageInterface $message): void
    {
        $this->messages[] = $message;
    }

    public function addMessages(array $messages): void
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }
    }

    public function hasException(): bool
    {
        return $this->exception !== null;
    }

    public function getException(): ?\Exception
    {
        return $this->exception;
    }

    public function throwException(string $exceptionClass = null): void
    {
        if (!$this->isSuccessful()) {
            if ($this->hasException()) {
                throw $this->getException();
            }
            $exceptionClass = $exceptionClass ?: \RuntimeException::class;
            throw new $exceptionClass($this->getMessagesAsString());
        }
    }

    public function has(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function get(string $name, $default = null)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return $default;
    }

    public function set(string $name, $value): self
    {
        $this->data[$name] = $value;

        return $this;
    }
}
