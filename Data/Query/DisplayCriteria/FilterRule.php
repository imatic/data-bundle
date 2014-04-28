<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

abstract class FilterRule
{
    /**
     * Filer name for compare
     *
     * @var string
     */
    protected $name;

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
     * Filer operator for compare
     *
     * @var string
     */
    protected $operator;

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

    public function __construct($name)
    {
        $this->bound = false;
        $this->name = $name;
        $this->formType = $this->getDefaultFormType();
        $this->formOptions = $this->getDefaultFormOptions();
        $this->setOperators($this->getDefaultOperators());
        $this->setOperator(reset($this->operators));
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
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param  mixed                     $value
     *                                          @return $this
     * @throws \InvalidArgumentException
     */
    public function setValue($value)
    {
        if (!$this->validateValue($value)) {
            $type = is_object($value) ? get_class($value) : gettype($value);
            throw new \InvalidArgumentException(sprintf('Binding invalid value (type "%s") into filter "%s"', $type, $this->name));
        }
        $this->bound = true;
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param  string                    $operator
     *                                             @return $this
     * @throws \InvalidArgumentException
     */
    public function setOperator($operator)
    {
        if ($operator) {
            if (!$this->validateOperator($operator)) {
                throw new \InvalidArgumentException(sprintf('Binding invalid operator "%s" into filter "%s"', $operator, $this->name));
            }
            $this->operator = $operator;
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
     * @param  array                     $operators
     * @throws \InvalidArgumentException
     */
    public function setOperators(array $operators)
    {
        $invalid = [];
        if (!$this->validateOperators($operators, $invalid)) {
            throw new \InvalidArgumentException(sprintf('Trying to set invalid operator(s) "%s" for filter "%s"', implode(', ', $operators), $this->getName()));
        }
        $this->operators = $operators;
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
     * @param  mixed $value
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
     * @param  string $operator
     * @return bool
     */
    protected function validateOperator($operator)
    {
        return in_array($operator, $this->getDefaultOperators());
    }

    /**
     * @param  array $operators
     * @param  array $invalid
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
