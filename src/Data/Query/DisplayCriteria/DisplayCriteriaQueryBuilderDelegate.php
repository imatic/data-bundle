<?php declare(strict_types=1);
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
    private FilterRuleProcessorDelegate $filterRuleProcessor;

    /**
     * @var DisplayCriteriaQueryBuilderInterface[]
     */
    private array $builders = [];

    public function __construct(FilterRuleProcessorDelegate $ruleProcessor)
    {
        $this->filterRuleProcessor = $ruleProcessor;
    }

    /**
     * @param DisplayCriteriaQueryBuilderInterface[] $builders
     */
    public function setBuilders(array $builders): void
    {
        $this->builders = $builders;
    }

    /**
     * @throws UnsupportedQueryBuilderException
     */
    private function getBuilderFor(object $qb): DisplayCriteriaQueryBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($qb)) {
                return $builder;
            }
        }

        throw new UnsupportedQueryBuilderException($qb);
    }

    public function apply(object $qb, QueryObjectInterface $queryObject, DisplayCriteriaInterface $displayCriteria = null): void
    {
        if (null !== $displayCriteria) {
            $this->applyPager($qb, $displayCriteria->getPager());
            $this->applyFilter($qb, $displayCriteria->getFilter(), $queryObject);
            $this->applySorter($qb, $displayCriteria->getSorter(), $queryObject);
        }
    }

    public function applyPager(object $qb, PagerInterface $pager): void
    {
        if ($pager->isEnabled()) {
            $this->getBuilderFor($qb)->applyPager($qb, $pager);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function applyFilter(object $qb, FilterInterface $filter, QueryObjectInterface $queryObject): void
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
                    throw new \InvalidArgumentException(\sprintf(
                        'Column "%s" is not present in the filter map',
                        $filterRule->getName()
                    ));
                }

                $this->filterRuleProcessor->process($qb, $filterRule, $filterMap[$filterRule->getName()]);
            }
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function applySorter(object $qb, SorterInterface $sorter, QueryObjectInterface $queryObject): void
    {
        if ($queryObject instanceof SortableQueryObjectInterface) {
            // default sorting if no sorter rules exists
            if (0 === $sorter->count() && 0 < \count($queryObject->getDefaultSort())) {
                $sorter->setSorterRules($queryObject->getDefaultSort());
            }

            $this->getBuilderFor($qb)->applySorter($qb, $sorter, $queryObject->getSorterMap());
        }
    }
}
