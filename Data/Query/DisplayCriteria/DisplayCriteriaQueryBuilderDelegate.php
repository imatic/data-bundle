<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Exception\UnsupportedQueryBuilderException;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 * @author Stepan Koci <stepan.koci@imatic.cz>
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
class DisplayCriteriaQueryBuilderDelegate
{
    /** @var FilterRuleProcessorDelegate */
    private $filterRuleProcessor;
    /** @var DisplayCriteriaQueryBuilderInterface[] */
    private $builders;

    public function __construct(FilterRuleProcessorDelegate $ruleProcessor)
    {
        $this->filterRuleProcessor = $ruleProcessor;
    }

    /**
     * @param DisplayCriteriaQueryBuilderInterface[] $builders
     */
    public function setBuilders(array $builders)
    {
        $this->builders = $builders;
    }

    /**
     * @param object $qb
     * @throws UnsupportedQueryBuilderException
     * @return DisplayCriteriaQueryBuilderInterface
     */
    private function getBuilderFor($qb)
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($qb)) {
                return $builder;
            }
        }

        throw new UnsupportedQueryBuilderException($qb);
    }

    /**
     * @param object                   $qb
     * @param QueryObjectInterface     $queryObject
     * @param DisplayCriteriaInterface $displayCriteria
     */
    public function apply($qb, QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null)
    {
        if (null !== $displayCriteria) {
            $this->applyPager($qb, $displayCriteria->getPager());
            $this->applyFilter($qb, $displayCriteria->getFilter(), $queryObject);
            $this->applySorter($qb, $displayCriteria->getSorter(), $queryObject);
        }
    }

    /**
     * @param object         $qb
     * @param PagerInterface $pager
     */
    public function applyPager($qb, PagerInterface $pager)
    {
        if ($pager->isEnabled()) {
            $this->getBuilderFor($qb)->applyPager($qb, $pager);
        }
    }

    /**
     * @param object               $qb
     * @param FilterInterface      $filter
     * @param QueryObjectInterface $queryObject
     * @throws \InvalidArgumentException
     */
    public function applyFilter($qb, FilterInterface $filter, QueryObjectInterface $queryObject)
    {
        if ($queryObject instanceof FilterableQueryObjectInterface) {
            $filterMap = $queryObject->getFilterMap();

            /* @var $filterRule FilterRule */
            foreach ($filter as $filterRule) {
                // rule must be bound
                if (!$filterRule->isBound()) {
                    continue;
                }

                // rule must be present in the filter map
                if (!isset($filterMap[$filterRule->getName()])) {
                    throw new \InvalidArgumentException(sprintf(
                        'Column "%s" is not present in the filter map',
                        $filterRule->getName()
                    ));
                }

                $this->filterRuleProcessor->process($qb, $filterRule, $filterMap[$filterRule->getName()]);
            }
        }
    }

    /**
     * @param object               $qb
     * @param SorterInterface      $sorter
     * @param QueryObjectInterface $queryObject
     * @throws \InvalidArgumentException
     */
    public function applySorter($qb, SorterInterface $sorter, QueryObjectInterface $queryObject)
    {
        if ($queryObject instanceof SortableQueryObjectInterface) {
            // default sorting if no sorter rules exists
            if (0 === $sorter->count() && 0 < count($queryObject->getDefaultSort())) {
                $sorter->setSorterRules($queryObject->getDefaultSort());
            }

            $this->getBuilderFor($qb)->applySorter($qb, $sorter, $queryObject->getSorterMap());
        }
    }
}
