<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Command;

class Message implements MessageInterface
{
    private string $type;
    private ?string $prefix = null;
    private ?string $text;
    private ?string $translationDomain;

    /**
     * @var mixed[]
     */
    private array $parameters;

    /**
     * @param mixed[] $parameters
     */
    public function __construct(string $type, string $text = null, array $parameters = [], string $translationDomain = null)
    {
        $this->type = $type;
        $this->text = $text;
        $this->parameters = $parameters;
        $this->translationDomain = $translationDomain;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getMessage(): string
    {
        $prefix = $this->prefix ? $this->prefix . '.' : '';

        return $prefix . $this->text;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(string $translationDomain): self
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getText();
    }
}
