<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\ArrayReader;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ArrayDisplayCriteriaFactory extends DisplayCriteriaFactory
{
    public function __construct(PagerFactory $pagerFactory, FormFactoryInterface $formFactory)
    {
        parent::__construct($pagerFactory, $formFactory, new ArrayReader());
    }

    public function setAttributes(array $attributes = [])
    {
        $this->displayCriteriaReader = new ArrayReader($attributes);
    }
}
