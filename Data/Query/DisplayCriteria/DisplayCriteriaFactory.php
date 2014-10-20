<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Form\Type\Filter\FilterType;
use Symfony\Component\Form\FormFactoryInterface;

abstract class DisplayCriteriaFactory
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
     * @param PagerFactory         $pagerFactory
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(PagerFactory $pagerFactory, FormFactoryInterface $formFactory)
    {
        $this->pagerFactory = $pagerFactory;
        $this->formFactory = $formFactory;
    }

    /**
     * @param  array           $options
     * @return DisplayCriteria
     */
    public function createCriteria(array $options = [])
    {
        $componentId = isset($options['componentId']) ? $options['componentId'] : null;
        $filter = isset($options['filter']) ? $options['filter'] : null;
        $sorter = isset($options['sorter']) ? $options['sorter'] : [];

        return new DisplayCriteria(
            $this->createPager($componentId),
            $this->createSorter($componentId, $sorter),
            $this->createFilter($componentId, $filter)
        );
    }

    /**
     * @param  string|null    $componentId
     * @return PagerInterface
     */
    public function createPager($componentId = null)
    {
        return $this->pagerFactory->createPager(
            $this->getAttribute('page', null, $componentId),
            $this->getAttribute('limit', null, $componentId)
        );
    }

    /**
     * @param  string|null          $componentId
     * @param  FilterInterface|null $filter
     * @return FilterInterface
     */
    public function createFilter($componentId = null, FilterInterface $filter = null)
    {
        if (!is_null($filter)) {
            $filterData = $this->getAttribute('filter', null, $componentId, true);
            
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
                $this->clearAttribute('filter', $componentId, []);
            }

            // unset filter data if defaults are requested
            if ($defaultFilter) {
                $this->clearAttribute('filter', $componentId, null);
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
     * @return SorterInterface
     */
    public function createSorter($componentId = null, array $sorter = [])
    {
        $sorterData = $this->getAttribute('sorter', $sorter, $componentId, true);

        $sorterRules = [];
        foreach ($sorterData as $fieldName => $direction) {
            $sorterRules[] = new SorterRule($fieldName, $direction);
        }

        return new Sorter($sorterRules);
    }

    /**
     * @param  string      $name
     * @param  mixed|null  $default
     * @param  string|null $component
     * @param  bool        $persistent
     * @return mixed
     */
    abstract protected function getAttribute($name, $default = null, $component = null, $persistent = true);

    /**
     * @param  string      $name
     * @param  string|null $component
     * @param  mixed       $emptyValue
     */
    abstract protected function clearAttribute($name, $component = null, $emptyValue = null);
}
