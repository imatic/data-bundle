<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
interface DisplayCriteriaQueryBuilderApplierInterface
{
    /**
     * @param mixed                         $qb
     * @param DisplayCriteriaInterface|null $displayCriteria
     */
    public function apply($qb, DisplayCriteriaInterface $displayCriteria = null);
}
