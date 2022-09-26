<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

interface MessageInterface
{
    public function getType(): string;

    /**
     * @return mixed[]
     */
    public function getParameters(): array;

    public function getMessage(): string;

    public function getText(): ?string;

    public function getTranslationDomain(): ?string;

    public function setTranslationDomain(string $translationDomain): self;

    public function setPrefix(string $prefix): self;

    public function getPrefix(): ?string;

    public function __toString();
}
