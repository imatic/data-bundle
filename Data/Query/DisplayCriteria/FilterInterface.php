<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Symfony\Component\Form\FormInterface;

interface FilterInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param int $index
     * @return FilterRule
     */
    public function getAt($index);

    /**
     * @return FormInterface
     */
    public function getForm();
}
