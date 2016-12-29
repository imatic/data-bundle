<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class FilterRule
{
    /**
     * Filer name for compare.
     *
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var bool
     *
     * @deprecated Use method isBound instead
     * If you wanna set this value, you should override the method instead
     */
    protected $bound;

    /**
     * Filer value for compare.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Filer operator for compare.
     *
     * @var string
     */
    protected $operator;

    /**
     * Allowed operators.
     *
     * @var string|null|array
     */
    protected $operators;

    /**
     * Form type.
     *
     * @var string
     */
    protected $formType;

    /**
     * Form options.
     *
     * @var array
     */
    protected $formOptions;

    /**
     * @var string
     */
    protected $type;

    public function __construct($name, array $options = [])
    {
        $this->name = $name;
        $this->options = $this->processOptions($options);
        $this->formType = $this->getDefaultFormType();
        $this->formOptions = $this->getDefaultFormOptions();
        $this->setOperators($this->getDefaultOperators());
        $this->setOperator($this->getDefaultOperator());
        $this->updateBound();
    }

    /**
     * @return bool
     */
    public function isBound()
    {
        return $this->bound;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @throws \OutOfBoundsException
     *
     * @return mixed
     */
    public function getOption($name)
    {
        if (!array_key_exists($name, $this->options)) {
            throw new \OutOfBoundsException(sprintf('Unknown option "%s"', $name));
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
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setValue($value)
    {
        if (null !== $value) {
            if (!$this->validateValue($value)) {
                throw new \InvalidArgumentException(sprintf(
                    'Binding invalid value (type "%s") into filter "%s" (%s)',
                    is_object($value) ? get_class($value) : gettype($value),
                    $this->name,
                    get_class($this)
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
        if (func_num_args() > 0) {
            if (null !== $value && $this->validateValue($value)) {
                $this->value = $value;
                $this->updateBound();

                return true;
            } else {
                return false;
            }
        } else {
            return $this->value;
        }
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setOperator($operator)
    {
        if ($operator) {
            if (!$this->validateOperator($operator)) {
                throw new \InvalidArgumentException(sprintf('Binding invalid operator "%s" into filter "%s"', $operator, $this->name));
            }
            $this->operator = $operator;
            $this->updateBound();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getOperators()
    {
        return $this->operators;
    }

    /**
     * @param array $operators
     *
     * @throws \InvalidArgumentException
     */
    public function setOperators(array $operators)
    {
        $invalid = [];
        if (!$this->validateOperators($operators, $invalid)) {
            throw new \InvalidArgumentException(sprintf('Trying to set invalid operator(s) "%s" for filter "%s"', implode(', ', $operators), $this->getName()));
        }
        $this->operators = $operators;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * @return array
     */
    public function getFormOptions()
    {
        return $this->formOptions;
    }

    /**
     * @param string $translationDomain
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->formOptions['translation_domain'] = $translationDomain;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function processOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_parameter_format' => '%s',
        ]);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    abstract protected function validateValue($value);

    /**
     * @return array
     */
    abstract protected function getDefaultOperators();

    /**
     * @return string|null
     */
    protected function getDefaultOperator()
    {
        return reset($this->operators);
    }

    /**
     * @return string
     */
    abstract protected function getDefaultFormType();

    /**
     * @return array
     */
    protected function getDefaultFormOptions()
    {
        return [];
    }

    /**
     * @param string $operator
     *
     * @return bool
     */
    protected function validateOperator($operator)
    {
        return in_array($operator, $this->getDefaultOperators());
    }

    /**
     * @param array $operators
     * @param array $invalid
     *
     * @return bool
     */
    protected function validateOperators($operators, array &$invalid = [])
    {
        $defaultOperators = $this->getDefaultOperators();

        foreach ($operators as $operator) {
            if (!in_array($operator, $defaultOperators)) {
                $invalid[] = $operator;
            }
        }

        return 0 === count($invalid);
    }

    public function reset()
    {
        $this->value = null;
        $this->updateBound();
    }

    private function updateBound()
    {
        $this->bound = $this->value !== null ||
            in_array($this->operator, [FilterOperatorMap::OPERATOR_EMPTY, FilterOperatorMap::OPERATOR_NOT_EMPTY]);
    }
}
