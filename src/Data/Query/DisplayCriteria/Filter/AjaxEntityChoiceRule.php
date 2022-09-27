<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\FormBundle\Form\Type\AjaxEntityChoiceType;

class AjaxEntityChoiceRule extends FilterRule
{
    private string $class;
    private string $route;

    public function __construct(string $name, string $class, string $route, array $options = [])
    {
        $this->class = $class;
        $this->route = $route;

        parent::__construct($name, $options);
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
        return AjaxEntityChoiceType::class;
    }

    protected function getDefaultFormOptions(): array
    {
        return [
            'multiple' => true,
            'class' => $this->class,
            'route' => $this->route,
        ];
    }

    public function setValue($value): self
    {
        parent::setValue($value);

        if (null !== $this->value && $this->value->count() < 1) {
            $this->reset();
        }

        return $this;
    }

    protected function validateValue($value): bool
    {
        return $value instanceof ArrayCollection;
    }

    public function getValue()
    {
        if (null !== $this->value) {
            $ids = [];

            foreach ($this->value as $entity) {
                $ids[] = $entity->getId();
            }

            return $ids;
        }
    }
}
