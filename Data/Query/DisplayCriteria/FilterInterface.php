<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\Form\FormInterface;

interface FilterInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param string $index
     * @return FilterRule
     */
    public function get($index);

    /**
     * @return FormInterface
     */
    public function getForm();

    /**
     * @return string
     */
    public function getTranslationDomain();
}
