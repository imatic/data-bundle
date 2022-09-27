<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChoiceRule extends FilterRule
{
    protected bool $multiple = false;

    /**
     * @var mixed[]
     */
    protected array $choices = [];

    /**
     * @param mixed[] $choices
     * @param mixed[] $options
     */
    public function __construct(string $name, array $choices, bool $multiple = false, string $type = null, array $options = [])
    {
        parent::__construct($name, $options);

        $this->choices = $choices;
        $this->multiple = $multiple;

        if (null !== $type) {
            $this->formType = $type;
        }
    }

    /**
     * @param mixed[] $choices
     */
    public function setChoices(array $choices): self
    {
        $this->choices = $choices;

        return $this;
    }

    public function setMultiple(bool $bool = true): self
    {
        $this->multiple = $bool;

        return $this;
    }

    protected function getDefaultOperators(): array
    {
        return [
            FilterOperatorMap::OPERATOR_IN,
            FilterOperatorMap::OPERATOR_NOT_IN,
        ];
    }

    protected function validateValue($value): bool
    {
        return
            \is_scalar($value)
            || (
                \is_array($value)
                && \count(\array_filter($value, 'is_scalar')) === \count($value)
            );
    }

    protected function getDefaultFormType(): string
    {
        return ChoiceType::class;
    }

    protected function getDefaultFormOptions(): array
    {
        return [
            'choices' => &$this->choices,
            'multiple' => &$this->multiple,
        ];
    }
}
