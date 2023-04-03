<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\Form\FormInterface;

class Filter implements FilterInterface
{
    /**
     * @var FilterRule[]
     */
    protected array $rules = [];
    protected ?FormInterface $form = null;
    protected bool $hasDefaults = false;

    /**
     * @var string|null|false
     */
    protected $translationDomain = null;

    public function __construct()
    {
        $this->configure();
    }

    public function boundCount(): int
    {
        return \array_reduce($this->rules, function ($count, FilterRule $rule) {
            return $rule->isBound() ? $count + 1 : $count;
        }, 0);
    }

    /**
     * @return FilterRule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param mixed $index
     */
    public function get($index): ?FilterRule
    {
        return $this->rules[$index] ?? null;
    }

    /**
     * @param mixed $index
     */
    public function has($index): bool
    {
        return isset($this->rules[$index]);
    }

    public function hasDefaults(): bool
    {
        return $this->hasDefaults;
    }

    /**
     * @param mixed $index
     */
    public function remove($index): void
    {
        unset($this->rules[$index]);
    }

    public function add(FilterRule $rule): self
    {
        $this->rules[$rule->getName()] = $rule;
        $rule->setFilter($this);

        if (!\is_null($rule->getValue())) {
            $this->hasDefaults = true;
        }

        return $this;
    }

    public function getForm(): ?FormInterface
    {
        return $this->form;
    }

    /**
     * @throws \LogicException
     */
    public function setForm(FormInterface $form): void
    {
        if ($this->form) {
            throw new \LogicException('Form is already set.');
        }

        $this->form = $form;
    }

    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    /**
     * @param string|null|false $translationDomain
     */
    public function setTranslationDomain($translationDomain): self
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        if (!($value instanceof FilterRule) || $offset !== $value->getName()) {
            throw new \InvalidArgumentException('Value must be a instance of FilterRule and index must be same as rule name');
        }
        $this->add($value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

    /**
     * Retrieve an external iterator.
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->rules);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->rules);
    }

    protected function configure(): void
    {
    }
}
