<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

abstract class FilterRule
{
    /**
     * @var bool
     */
    protected $bound;

    /**
     * Filer value for compare
     *
     * @var mixed
     */
    protected $value;

    /**
     * Default filer value
     *
     * @var mixed
     */
    protected $default;

    /**
     * Filer operator for compare
     *
     * @var string
     */
    protected $operator;

    /**
     * Filer name for compare
     *
     * @var string
     */
    protected $name;

    /**
     * Allowed operators
     *
     * @var string|null|array
     */
    protected $operators;

    /**
     * Form type
     *
     * @var string
     */
    protected $formType;

    /**
     * Form options
     *
     * @var array
     */
    protected $formOptions;

    public function __construct($name, $formType = null, array $formOptions = [], array $operators = null)
    {
        $this->bound = false;
        $this->name = $name;

        $this->formType = $formType ? : $this->getDefaultFormType();
        $this->formOptions = array_merge($this->getDefaultFormOptions(), $formOptions);

        if (is_null($operators)) {
            $this->operators = $this->getDefaultOperators();
        } else {
            $invalid = [];
            if (!$this->validateOperators($operators, $invalid)) {
                throw new \InvalidArgumentException(sprintf('Trying to set invalid operator(s) "%s" for filter "%s"', implode(', ', $operators), $name));
            }
            $this->operators = $operators;
        }
    }

    public function bind($value, $operator = null)
    {
        if (!$this->validateValue($value)) {
            $type = is_object($value) ? get_class($value) : gettype($value);
            throw new \InvalidArgumentException(sprintf('Binding invalid value (type "%s") into filter "%s"', $type, $this->name));
        }

        if (empty($operator)) {
            $operator = $this->getDefaultOperators()[0];
        }

        if (!$this->validateOperator($operator)) {
            throw new \InvalidArgumentException(sprintf('Binding invalid operator "%s" into filter "%s"', $operator, $this->name));
        }

        $this->value = $value;
        $this->operator = $operator;
        $this->bound = true;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasDefault()
    {
        return !is_null($this->default);
    }

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
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return array|null
     */
    public function getOperators()
    {
        return $this->operators;
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
     * @param string $value
     * @return bool
     */
    abstract protected function validateValue($value);

    /**
     * @return array
     */
    abstract protected function getDefaultOperators();

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
     * @return bool
     */
    protected function validateOperator($operator)
    {
        return in_array($operator, $this->getDefaultOperators());
    }

    /**
     * @param array $operators
     * @param array $invalid
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
}