<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\DisplayCriteriaReader;
use Imatic\Bundle\DataBundle\Form\Type\Filter\FilterType;
use Symfony\Component\Form\FormFactoryInterface;

class DisplayCriteriaFactory
{
    protected PagerFactory $pagerFactory;
    private FormFactoryInterface $formFactory;
    protected DisplayCriteriaReader $displayCriteriaReader;

    public function __construct(
        PagerFactory $pagerFactory,
        FormFactoryInterface $formFactory,
        DisplayCriteriaReader $displayCriteriaReader
    ) {
        $this->pagerFactory = $pagerFactory;
        $this->formFactory = $formFactory;
        $this->displayCriteriaReader = $displayCriteriaReader;
    }

    /**
     * @param mixed[] $options
     */
    public function createCriteria(array $options = [], bool $persistent = false): DisplayCriteria
    {
        $componentId = $options['componentId'] ?? null;
        $pager = $options['pager'] ?? [];
        $filter = $options['filter'] ?? null;
        $sorter = $options['sorter'] ?? [];

        return new DisplayCriteria(
            $this->createPager($componentId, $pager, $persistent),
            $this->createSorter($componentId, $sorter, $persistent),
            $this->createFilter($componentId, $filter, $persistent)
        );
    }

    /**
     * @param mixed[] $pager
     */
    public function createPager(string $componentId = null, array $pager = [], bool $persistent = false): PagerInterface
    {
        return $this->pagerFactory->createPager(
            (int) $this->displayCriteriaReader->readAttribute(
                DisplayCriteriaReader::PAGE,
                $pager[DisplayCriteriaReader::PAGE] ?? null,
                $componentId,
                $persistent
            ),
            (int) $this->displayCriteriaReader->readAttribute(
                DisplayCriteriaReader::LIMIT,
                $pager[DisplayCriteriaReader::LIMIT] ?? null,
                $componentId,
                $persistent
            )
        );
    }

    public function createFilter(string $componentId = null, FilterInterface $filter = null, bool $persistent = false): FilterInterface
    {
        if (!\is_null($filter)) {
            $filterData = $this
                ->displayCriteriaReader
                ->readAttribute(DisplayCriteriaReader::FILTER, null, $componentId, $persistent)
            ;

            $clearFilter = null !== $filterData && isset($filterData['clearFilter']);
            $defaultFilter = null !== $filterData && isset($filterData['defaultFilter']);

            // reset rules (this removes defaults from them)
            if (!$defaultFilter && ($clearFilter || null !== $filterData)) {
                foreach ($filter as $rule) {
                    $rule->reset();
                }
            }

            // reset filter data
            if (null === $filterData || $clearFilter || $defaultFilter) {
                $filterData = [];
            }

            // make filter data empty if the filters are cleared
            if ($clearFilter) {
                $this->displayCriteriaReader->clearAttribute(DisplayCriteriaReader::FILTER, $componentId, []);
            }

            // unset filter data if defaults are requested
            if ($defaultFilter) {
                $this->displayCriteriaReader->clearAttribute(DisplayCriteriaReader::FILTER, $componentId, null);
            }

            $form = $this->formFactory->createNamed('filter', FilterType::class, $filter, [
                'filter' => $filter,
                'translation_domain' => $filter->getTranslationDomain(),
            ]);
            $form->submit($filterData, false);

            $filter = $form->getData();
            $filter->setForm($form);

            return $filter;
        }

        return new Filter();
    }

    /**
     * @param mixed[] $sorter
     */
    public function createSorter(string $componentId = null, array $sorter = [], bool $persistent = false): SorterInterface
    {
        $sorterData = $this
            ->displayCriteriaReader
            ->readAttribute(DisplayCriteriaReader::SORTER, $sorter, $componentId, $persistent);

        $sorterRules = [];
        foreach ($sorterData as $fieldName => $direction) {
            $sorterRules[] = new SorterRule($fieldName, $direction);
        }

        return new Sorter($sorterRules);
    }
}
