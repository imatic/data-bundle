<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Form\Type\Filter\FilterType;
use Symfony\Component\Form\FormFactoryInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\DisplayCriteriaReader;

class DisplayCriteriaFactory
{
    /**
     * @var PagerFactory
     */
    protected $pagerFactory;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var DisplayCriteriaReader
     */
    protected $displayCriteriaReader;

    /**
     * @param PagerFactory         $pagerFactory
     * @param FormFactoryInterface $formFactory
     * @param DisplayCriteriaReader $displayCriteriaReader
     */
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
     * @param  array           $options
     * @param  bool            $persistent
     * @return DisplayCriteria
     */
    public function createCriteria(array $options = [], $persistent = false)
    {
        $componentId = isset($options['componentId']) ? $options['componentId'] : null;
        $filter = isset($options['filter']) ? $options['filter'] : null;
        $sorter = isset($options['sorter']) ? $options['sorter'] : [];

        return new DisplayCriteria(
            $this->createPager($componentId, $persistent),
            $this->createSorter($componentId, $sorter, $persistent),
            $this->createFilter($componentId, $filter, $persistent)
        );
    }

    /**
     * @param  string|null    $componentId
     * @param  bool           $persistent
     * @return PagerInterface
     */
    public function createPager($componentId = null, $persistent = false)
    {
        return $this->pagerFactory->createPager(
            $this->displayCriteriaReader->readAttribute(DisplayCriteriaReader::PAGE, null, $componentId, $persistent),
            $this->displayCriteriaReader->readAttribute(DisplayCriteriaReader::LIMIT, null, $componentId, $persistent)
        );
    }

    /**
     * @param  string|null          $componentId
     * @param  FilterInterface|null $filter
     * @param  bool                 $persistent
     * @return FilterInterface
     */
    public function createFilter($componentId = null, FilterInterface $filter = null, $persistent = false)
    {
        if (!is_null($filter)) {
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

            $form = $this->formFactory->createNamed('filter', new FilterType(), $filter, [
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
     * @param  string|null     $componentId
     * @param  array           $sorter
     * @param  bool            $persistent
     * @return SorterInterface
     */
    public function createSorter($componentId = null, array $sorter = [], $persistent = false)
    {
        $sorterData = $this
            ->displayCriteriaReader
            ->readAttribute(DisplayCriteriaReader::SORTER, $sorter, $componentId, $persistent)
        ;

        $sorterRules = [];
        foreach ($sorterData as $fieldName => $direction) {
            $sorterRules[] = new SorterRule($fieldName, $direction);
        }

        return new Sorter($sorterRules);
    }
}
