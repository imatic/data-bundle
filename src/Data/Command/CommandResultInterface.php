<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

interface CommandResultInterface
{
    public function getMessagesAsString(): string;

    /**
     * @return MessageInterface[]
     */
    public function getMessages(): array;

    public function hasMessages(): bool;

    public function isSuccessful(): bool;

    public function hasException(): bool;

    public function getException(): ?\Exception;

    /**
     * @throws \Exception
     */
    public function throwException(string $exceptionClass = null): void;

    public function addMessage(MessageInterface $message): void;

    /**
     * @param MessageInterface[] $messages
     */
    public function addMessages(array $messages): void;

    public function has(string $name): bool;

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * @param mixed $value
     */
    public function set(string $name, $value): self;
}
