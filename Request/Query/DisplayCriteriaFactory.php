<?php
namespace Imatic\Bundle\DataBundle\Request\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Form\Type\Filter\FilterType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @todo Podle typu dat (json/xml...) pridat servanty
 * ($this->servants['json']->getCriteria($displayCriteriaData))
 */
class DisplayCriteriaFactory
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var PagerFactory
     */
    protected $pagerFactory;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param RequestStack         $requestStack
     * @param PagerFactory         $pagerFactory
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(RequestStack $requestStack, PagerFactory $pagerFactory, FormFactoryInterface $formFactory)
    {
        $this->requestStack = $requestStack;
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
            $filterData = $this->getAttribute('filter', [], $componentId);

            if (isset($filterData['clearFilter']) || $filterData) {
                foreach ($filter as $rule) {
                    $rule->reset();
                }
            }

            if (isset($filterData['clearFilter'])) {
                $filterData = [];
            }

            $form = $this->formFactory->createNamed('filter', new FilterType(), $filter, [
                'filter' => $filter,
                'translation_domain' => $filter->getTranslationDomain(),
            ]);
            $form->submit($filterData, false);

            if ($form->isValid()) {
                $filter = $form->getData();
                $filter->setForm($form);

                return $filter;
            }
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
        $sorterData = $this->getAttribute('sorter', $sorter, $componentId);

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
     * @return mixed
     */
    protected function getAttribute($name, $default = null, $component = null)
    {
        $request = $this->requestStack->getCurrentRequest();

        $path = $name;
        if ($component) {
            $path = $component . '[' . $name . ']';
        }

        return $request->query->get($path, $default, true);
    }
}
