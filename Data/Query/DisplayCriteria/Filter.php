<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\Form\FormInterface;

class Filter implements FilterInterface
{
    /**
     * @var FilterRule[]
     */
    protected $filterRules = array();

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var string
     */
    protected $translationDomain;

    public function __construct()
    {
        $this->configure();
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->filterRules);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->filterRules);
    }

    public function boundCount()
    {
        return array_reduce($this->filterRules, function ($count, FilterRule $rule) {
            if ($rule->isBound()) $count++;

            return $count;
        }, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function get($index)
    {
        return $this->filterRules[$index];
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
     * @return FilterRule[]
     */
    public function getRules()
    {
        return $this->filterRules;
    }

    /**
     * @param FilterRule $rule
     * @return $this
     */
    public function addRule(FilterRule $rule)
    {
        $this->filterRules[] = $rule;

        return $this;
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
     * @return $this
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    protected function configure()
    {
    }
}
