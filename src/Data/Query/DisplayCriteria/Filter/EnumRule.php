<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class EnumRule extends FilterRule
{
    protected string $class;
    protected bool $multiple = false;

    /**
     * @var callable
     */
    protected $choiceLabel;

    public function __construct(string $name, string $class, array $options = [])
    {
        $this->class = $class;

        $this->choiceLabel = static function (\UnitEnum $choice): string {
            return $choice->name;
        };

        parent::__construct($name, $options);
    }

    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function setChoiceLabel(callable $callback): self
    {
        $this->choiceLabel = $callback;

        return $this;
    }

    public function getValue()
    {
        if ($this->multiple && !empty($this->value)) {
            $value = [];

            foreach ($this->value as $item) {
                $value[] = $item->value;
            }

            return $value;
        }

        return parent::getValue();
    }

    protected function validateValue($value): bool
    {
        return $value instanceof $this->class
            || (
                \is_array($value)
                && \count(array_filter($value, fn ($val) => $val instanceof $this->class)) === \count($value)
            );
    }

    protected function getDefaultOperators(): array
    {
        return [
            FilterOperatorMap::OPERATOR_IN,
            FilterOperatorMap::OPERATOR_NOT_IN,
        ];
    }

    protected function getDefaultFormType(): string
    {
        return EnumType::class;
    }

    protected function getDefaultFormOptions(): array
    {
        return [
            'class' => $this->class,
            'multiple' => &$this->multiple,
            'choice_label' => &$this->choiceLabel,
        ];
    }
}
