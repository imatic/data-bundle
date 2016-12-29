<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\Form\FormInterface;

class Filter implements FilterInterface
{
    /**
     * @var FilterRule[]
     */
    protected $rules = array();

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var string
     */
    protected $translationDomain;

    /**
     * @var bool
     */
    protected $hasDefaults = false;

    public function __construct()
    {
        $this->configure();
    }

    public function boundCount()
    {
        return array_reduce($this->rules, function ($count, FilterRule $rule) {
            return $rule->isBound() ? $count + 1 : $count;
        }, 0);
    }

    /**
     * @return FilterRule[]
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function get($index)
    {
        return $this->rules[$index];
    }

    /**
     * @param $index
     *
     * @return bool
     */
    public function has($index)
    {
        return isset($this->rules[$index]);
    }

    /**
     * @return bool
     */
    public function hasDefaults()
    {
        return $this->hasDefaults;
    }

    /**
     * @param string $index
     */
    public function remove($index)
    {
        unset($this->rules[$index]);
    }

    /**
     * @param FilterRule $rule
     *
     * @return $this
     */
    public function add(FilterRule $rule)
    {
        $this->rules[$rule->getName()] = $rule;

        if (!is_null($rule->getValue())) {
            $this->hasDefaults = true;
        }

        return $this;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param FormInterface $form
     *
     * @throws \LogicException
     */
    public function setForm(FormInterface $form)
    {
        if ($this->form) {
            throw new \LogicException('Form is already set.');
        }
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    /**
     * @param string $translationDomain
     *
     * @return $this
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    public function offsetExists($index)
    {
        return $this->has($index);
    }

    public function offsetGet($index)
    {
        return $this->get($index);
    }

    public function offsetSet($index, $value)
    {
        if (!($value instanceof FilterRule) || $index != $value->getName()) {
            throw new \InvalidArgumentException('Value must be a instance of FilterRule and index must be same as rule name');
        }
        $this->add($value);
    }

    public function offsetUnset($index)
    {
        $this->remove($index);
    }

    /**
     * Retrieve an external iterator.
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->rules);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->rules);
    }

    protected function configure()
    {
    }
}
