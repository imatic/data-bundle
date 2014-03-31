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
     * @param FilterRule[] $filterRules
     */
    public function __construct(array $filterRules = [])
    {
        $this->filterRules = $filterRules;
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

    /**
     * {@inheritdoc}
     */
    public function getAt($index)
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
}
