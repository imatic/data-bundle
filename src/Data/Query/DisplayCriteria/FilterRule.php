<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class FilterRule
{
    /**
     * File name for compare.
     */
    protected string $name;

    /**
     * @var mixed[]
     */
    protected array $options;

    /**
     * Filer value for compare.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Filer operator for compare.
     */
    protected ?string $operator = null;

    /**
     * Allowed operators.
     *
     * @var mixed[]
     */
    protected array $operators;

    protected string $formType;

    /** @var int|string|null */
    protected $type = null;

    /**
     * @var mixed[]
     */
    protected array $formOptions;

    private ?FilterInterface $filter = null;
    private bool $bound;

    /**
     * @param mixed[] $options
     */
    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;
        $this->options = $this->processOptions($options);
        $this->formType = $this->getDefaultFormType();
        $this->formOptions = $this->getDefaultFormOptions();
        $this->setOperators($this->getDefaultOperators());
        $this->setOperator($this->getDefaultOperator());
        $this->updateBound();
    }

    public function isBound(): bool
    {
        return $this->bound;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws \OutOfBoundsException
     *
     * @return mixed
     */
    public function getOption(string $name)
    {
        if (!\array_key_exists($name, $this->options)) {
            throw new \OutOfBoundsException(\sprintf('Unknown option "%s"', $name));
        }

        return $this->options[$name];
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     */
    public function setValue($value): self
    {
        if (null !== $value) {
            if (!$this->validateValue($value)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Binding invalid value (type "%s") into filter "%s" (%s)',
                    \is_object($value) ? \get_class($value) : \gettype($value),
                    $this->name,
                    \get_class($this)
                ));
            }

            $this->value = $value;
            $this->updateBound();
        }

        return $this;
    }

    /**
     * Get or set the value.
     *
     * Intended for use with property paths (e.g. inside form fields).
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function ruleValue($value = null)
    {
        if (\func_num_args() > 0) {
            if (null !== $value && $this->validateValue($value)) {
                $this->value = $value;
                $this->updateBound();

                return true;
            }
            return false;
        }
        return $this->value;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     *
     * @throws \InvalidArgumentException
     */
    public function setOperator($operator): self
    {
        if (\is_string($operator)) {
            if (!$this->validateOperator($operator)) {
                throw new \InvalidArgumentException(\sprintf('Binding invalid operator "%s" into filter "%s"', $operator, $this->name));
            }
            $this->operator = $operator;
            $this->updateBound();
        }

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getOperators(): array
    {
        return $this->operators;
    }

    /**
     * @param mixed[] $operators
     *
     * @throws \InvalidArgumentException
     */
    public function setOperators(array $operators): self
    {
        $invalid = [];
        if (!$this->validateOperators($operators, $invalid)) {
            throw new \InvalidArgumentException(\sprintf('Trying to set invalid operator(s) "%s" for filter "%s"', \implode(', ', $operators), $this->getName()));
        }
        $this->operators = $operators;

        return $this;
    }

    public function getFormType(): string
    {
        return $this->formType;
    }

    /**
     * @return mixed[]
     */
    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    /**
     * @param string|null|false $translationDomain
     */
    public function setTranslationDomain($translationDomain): self
    {
        $this->formOptions['translation_domain'] = $translationDomain;

        return $this;
    }

    /**
     * @param int|string|null $type
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed[] $options
     *
     * @return mixed[]
     */
    protected function processOptions(array $options): array
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        return $resolver->resolve($options);
    }

    protected function setDefaultOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'query_parameter_format' => '%s',
        ]);
    }

    /**
     * @param mixed $value
     */
    abstract protected function validateValue($value): bool;

    /**
     * @return array<int, string>
     */
    abstract protected function getDefaultOperators(): array;

    /**
     * @return false|mixed
     */
    protected function getDefaultOperator()
    {
        return \reset($this->operators);
    }

    abstract protected function getDefaultFormType(): string;

    /**
     * @return array<mixed>
     */
    protected function getDefaultFormOptions(): array
    {
        return [];
    }

    protected function validateOperator(string $operator): bool
    {
        return \in_array($operator, $this->getDefaultOperators(), true);
    }

    /**
     * @param mixed[] $operators
     * @param mixed[] $invalid
     */
    protected function validateOperators(array $operators, array &$invalid): bool
    {
        $defaultOperators = $this->getDefaultOperators();

        foreach ($operators as $operator) {
            if (!\in_array($operator, $defaultOperators, true)) {
                $invalid[] = $operator;
            }
        }

        return 0 === \count($invalid);
    }

    public function reset(): void
    {
        $this->value = null;
        $this->updateBound();
    }

    private function updateBound(): void
    {
        $this->bound = $this->value !== null ||
            \in_array($this->operator, [FilterOperatorMap::OPERATOR_EMPTY, FilterOperatorMap::OPERATOR_NOT_EMPTY], true);
    }

    public function getFilter(): ?FilterInterface
    {
        return $this->filter;
    }

    public function setFilter(FilterInterface $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}
